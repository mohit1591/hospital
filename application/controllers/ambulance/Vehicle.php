<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vehicle extends CI_Controller {
 
	function __construct() 
	{
		parent::__construct();	
		auth_users();  
		
        $this->load->model('general/general_model');
        $this->load->model('ambulance/vehicle_model','vehicle');
		    $this->load->library('form_validation');
    }

    
	public function index()
    {
            unauthorise_permission('348','2081');
            $this->session->unset_userdata('vehicle_search');
        $data['page_title'] = 'Ambulance Vehicle List'; 
        $this->load->model('default_search_setting/default_search_setting_model'); 
        $default_search_data = $this->default_search_setting_model->get_default_setting();
        if(isset($default_search_data[1]) && !empty($default_search_data) && $default_search_data[1]==1)
        {
            $start_date = '';
            $end_date = '';
        }
        else
        {
            $start_date = date('d-m-Y');
            $end_date = date('d-m-Y');
        }
        // End Defaul Search
        $this->load->model('general/general_model','general_model');
          $data['location_list'] = $this->general_model->location_list();
        $data['form_data'] = array('vehicle_no'=>'','location'=>'','chassis_no'=>'','engine_no'=>'','type'=>'','vehicle_type'=>'','owner_name'=>'','reg_date'=>'','reg_exp'=>'','start_date'=>$start_date,'end_date'=>$end_date); 
        $this->load->view('ambulance/vehicle/list',$data);
    }
    // public function add()
    // {   
    //     $data['page_title'] = 'Ambulance Registration'; 
    //     $this->load->view('ambulance/vehicle/add',$data);
    // }
         public function ajax_list()
    { 
      unauthorise_permission('348','2081');
      $users_data = $this->session->userdata('auth_users');
      $sub_branch_details = $this->session->userdata('sub_branches_data');
      $parent_branch_details = $this->session->userdata('parent_branches_data');
      $list = $this->vehicle->get_datatables();
       // print_r($list);die;
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $vehicle_list) {
         // print_r($ipd_perticular);die;
            $no++;
            $row = array();
            if($vehicle_list->status==1)
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
            if($users_data['parent_id']==$vehicle_list->branch_id)
            {
               $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$vehicle_list->id.'">'.$check_script;
            }else{
               $row[]='';
            }
            $row[] = $vehicle_list->vehicle_no;
            $row[] = $vehicle_list->chassis_no;
            $row[] = $vehicle_list->engine_no;
            
            if($vehicle_list->vehicle_type==1){ $vehicle_type='Self-Owned'; } else { $vehicle_type='Leased';}
			
            $row[] = $vehicle_type;
            
            //$row[] = $vehicle_list->type;
            // $row[] = $status;
             $row[] = date('d-M-Y',strtotime($vehicle_list->registration_date)); 
             $row[] = date('d-M-Y',strtotime($vehicle_list->registration_exp_date)); 
              $row[] = date('d-M-Y',strtotime($vehicle_list->created_date)); 
              $row[] = $vehicle_list->charge;
               $btnedit='';
            $btndelete='';
            $btnupload='';
            $btnview='';
          
            if($users_data['parent_id']==$vehicle_list->branch_id)
            {
              if(in_array('2083',$users_data['permission']['action'])){
               $btnedit =' <a onClick="return edit_vehicle_list('.$vehicle_list->id.');" class="btn-custom" href="javascript:void(0)" style="'.$vehicle_list->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
              }
             if(in_array('2084',$users_data['permission']['action'])){
                    $btndelete = ' <a class="btn-custom" onClick="return delete_driver_list('.$vehicle_list->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';   
                }
                
               if(in_array('2083',$users_data['permission']['action'])){
               $btnupload ='<a class="btn-custom" onclick="return upload_document('.$vehicle_list->id.')" href="javascript:void(0)" title="Upload Document"><i class="fa fa-info-circle"></i> Upload Document </a>';
              }
               if(in_array('2083',$users_data['permission']['action'])){
                    $btnview = '<a class="btn-custom" href="'.base_url('ambulance/vehicle/view_files/'.$vehicle_list->id.'/'.$vehicle_list->branch_id).'" title="View Document" data-url="512"><i class="fa fa-circle"></i> View Document Files</a>';   
                }
            }
          
             $row[] = $btnedit.$btndelete.$btnupload.$btnview;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                       "recordsTotal" => $this->vehicle->count_all(),
                        "recordsFiltered" => $this->vehicle->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
 

public function add($pid="")
    {
        unauthorise_permission('348','2082');

 //echo "<pre>";print_r($post);die;
		
        unauthorise_permission('348','2082');
        $data['page_title'] = 'Add Ambulance Vehicle'; 
        $this->load->model('general/general_model'); 
        // $data['country_list'] = $this->general_model->country_list();
        $data['type_list'] = $this->general_model->type_list();
          $data['location_list'] = $this->general_model->location_list();
		$data['vendor_list'] = $this->vehicle->vendor_list();

        $vendor_id='';
        $purchase_no = "";
        $vendor_code = "";
        $name = "";
        $mobile_no = "";
        $email = "";
        $address = "";
        $address2 = "";
        $address3 = "";
        $vendor_gst='';

       if($pid>0)
        {
           $vendor = $this->vehicle->get_vendor_by_id($pid);
           //print_r($vendor);
           if(!empty($vendor))
           {
              $vendor_id = $vendor['id'];
              $vendor_code = $vendor['vendor_id'];
              $name = $vendor['name'];
              $mobile_no = $vendor['mobile'];
              $address = $vendor['address'];
              $address2 = $vendor['address2'];
              $address3 = $vendor['address3'];
              $email = $vendor['email'];
              $vendor_gst = $vendor['vendor_gst'];
              } 
        }
        else
		{
          $vendor_code=generate_unique_id(11);
        }		

        $post = $this->input->post();
		
	
		
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                'data_id'=>"", 
                                'vehicle_no'=>"",
                                'chassis_no'=>"",
                                'engine_no'=>"",
                                
                                'registration_date'=>"",
                                'registration_exp_date'=>"",
        
                                'vehicle_type'=>"",
                                'owner_name'=>"",
                                'owner_mobile'=>"",
                                'owner_email'=>"",
                                'owner_address'=>"",
                                'gst_no'=>"",
                                'contact_from'=>"",
                                'contact_to'=>"",
								
								'charge'=>"",
								'charge_type'=>"",
								'location'=>"",
								
								"add_vendor_type"=> "",
								'vendor_id'=> $pid,
								"vendor_code"=> $vendor_code,
								"name"=>$name,
								"vendor_gst"=>$vendor_gst,
								"address"=>$address,
								"address2"=>$address2,
								"address3"=>$address3,
								"mobile"=>$mobile_no,
								"email"=>$email,
                        
                                  );   


        if(isset($post) && !empty($post))
        {   
          
         $data['form_data'] = $this->_validate();
             if($this->form_validation->run() == TRUE)
             {
                $this->vehicle->save();
                echo 1;
                return false;
                
            }
            else
            {

                $data['form_error'] = validation_errors();  
             
            }
        }
       $this->load->view('ambulance/vehicle/add',$data);       
    }
    

     public function edit($id="")
    {
	
     //unauthorise_permission('109','679');
     if(isset($id) && !empty($id) && is_numeric($id))
      {  
        $this->load->model('general/general_model'); 
        $data['type_list'] = $this->general_model->type_list();
         $data['location_list'] = $this->general_model->location_list();
        $result = $this->vehicle->get_by_id($id); 
        $data['vendor_list'] = $this->vehicle->vendor_list();
        $result_vendor = $this->vehicle->get_vendor_by_id($result['vendor_id']);
		
        $data['page_title'] = "Edit Vehicle Details";  
        $post = $this->input->post();
        $dob=date('d-m-Y',strtotime($result['dob']));
        $data['form_error'] = ''; 
        $data['form_data'] = array(
          'data_id'=>$result['id'],        
                                'vehicle_no'=>$result['vehicle_no'],
                                'chassis_no'=>$result['chassis_no'],
                                'engine_no'=>$result['engine_no'],
                                
                                'registration_date'=>date('d-m-Y ',strtotime($result['registration_date'])),
                                'registration_exp_date'=>date('d-m-Y ',strtotime($result['registration_exp_date'])),
                              
                                'vehicle_type'=>$result['vehicle_type'],
                                'owner_name'=>$result['owner_name'],
                                'owner_mobile'=>$result['owner_mobile'],
                                'owner_email'=>$result['owner_email'],
                                'owner_address'=>$result['owner_address'],
                                'gst_no'=>$result['gst_no'],
                                'contact_from'=>date('d-m-Y ',strtotime($result['contact_from'])),
                                'contact_to'=>date('d-m-Y ',strtotime($result['contact_to'])),
								
								'charge'=>$result['charge'],
								'charge_type'=>$result['charge_type'],
								'location'=>$result['location'],
								"add_vendor_type"=> $result['add_vendor_type'],
								"vendor_id"=> $result['vendor_id'],
								"vendor_code"=>$result_vendor['vendor_id'],
								"name"=>$result_vendor['name'],
								"vendor_gst"=>$result_vendor['vendor_gst'],
								"address"=>$result_vendor['address'],
								"address2"=>$result_vendor['address2'],
								"address3"=>$result_vendor['address3'],
								"mobile"=>$result_vendor['mobile'],
								"email"=>$result_vendor['email'],
                                 
                                  );   
       
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->vehicle->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('ambulance/vehicle/add',$data);       
      }
    }
    
    
    
 private function _validate()
    {
        $post = $this->input->post();  
       // print_r($post);die;
      
        $this->load->model('general/general_model'); 
      
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        if(empty($post['data_id']))
        {
            $this->form_validation->set_rules('vehicle_no', 'Vehicle No', 'trim|required|callback_check_unique_value'); 
            
        }
        else
        {
            $this->form_validation->set_rules('vehicle_no', 'Vehicle No', 'trim|required'); 
            
        }
        $this->form_validation->set_rules('engine_no', 'Engine No', 'trim|required'); 
        $this->form_validation->set_rules('chassis_no', 'Chassis No', 'trim|required'); 
        
		$this->form_validation->set_rules('location', 'Location', 'trim|required'); 
		
       if($post['vehicle_type']==2)
       {
         $this->form_validation->set_rules('charge', 'Owner charge', 'trim|required'); 
         $this->form_validation->set_rules('name', 'Owner name', 'trim|required'); 
         $this->form_validation->set_rules('mobile', 'Owner mobile no', 'trim|required');

       }
	   
	    if($post['add_vendor_type']==2 && $post['vehicle_type']==2)
       {

		  $this->form_validation->set_rules('vendor_id', 'vendor name', 'trim|required'); 
       }
 
        if ($this->form_validation->run() == FALSE) 
        {  
            $data['form_data'] = array(
                                'data_id'=>$post['data_id'], 
                                'vehicle_no'=>$post['vehicle_no'],
                                'chassis_no'=>$post['chassis_no'],
                                'engine_no'=>$post['engine_no'],
                                'registration_date'=>$post['registration_date'],
                                'registration_exp_date'=>$post['registration_exp_date'],
                               
                               
                                'vehicle_type'=>$post['vehicle_type'],
								'charge'=>$post['charge'],
								'charge_type'=>$post['charge_type'],
								'location'=>$post['location'],
								"add_vendor_type"=> $post['add_vendor_type'],
								"vendor_id"=> $post['vendor_id'],
								"vendor_code"=>$post['vendor_id'],
								"name"=>$post['name'],
								"vendor_gst"=>$post['vendor_gst'],
								"address"=>$post['address'],
								"address2"=>$post['address2'],
								"address3"=>$post['address3'],
								"mobile"=>$post['mobile'],
								"email"=>$post['email'],
                                       ); 
         
            //print '<pre>'; print_r($data['form_data']);die;
             return $data['form_data'];
            
        }
     //   return $data['form_data'];   
    }
    
    
  function get_vandor_data($vendor_id="")
  {
        $data['vendor_list'] = $this->vehicle->vendor_list($vendor_id);
        //print_r($data['vendor_list']);die;
        $post = $this->input->post();
        $data['form_data'] = array(
	
	                            "vendor_code"=>$data['vendor_list'][0]->vendor_id,
								"name"=>$data['vendor_list'][0]->name,
								"mobile"=>$data['vendor_list'][0]->mobile,
								"email"=>$data['vendor_list'][0]->email,
								"address"=>$data['vendor_list'][0]->address,
								"address2"=>$data['vendor_list'][0]->address2,
								"address3"=>$data['vendor_list'][0]->address3,
								"vendor_gst"=>$data['vendor_list'][0]->vendor_gst,
                                 ); 
         
          //print_r($data['form_data']);die;
		  $json = json_encode($data['form_data'],true);
          echo $json;
     
  }		

    public function delete($id="")
    {
       //unauthorise_permission('109','680');
       if(!empty($id) && $id>0)
       {
           
           $result = $this->vehicle->delete($id);
           $response = "Vehicle successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission('109','680');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->vehicle->deleteall($post['row_id']);
            $response = "Vehicles successfully deleted.";
            echo $response;
        }
    }

    public function vehicle_excel()
    {
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          // Field names in the first row
          $fields = array('Vehicle No','Chassis No','Engine no','Vehicle Type','Reg. Date','Reg. Exp.','Created Date','Charge');
          
          $col = 0;
           $objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           
            $objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            
             $objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           
          foreach ($fields as $field)
          {
               $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
                 $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
                
                
                $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
                
                
                $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
               
                $col++;
          }
          $list = $this->vehicle->search_report_data();

          $rowData = array();
          $data= array();
          if(!empty($list))
          {
               
               $m=1;
               foreach($list as $reports)
               {
                if($reports->vehicle_type==1)
                {
                 $vehicle_type='Self-Owned'; 
                }
                else{
                  $vehicle_type='Leased'; 
                }
                
               array_push($rowData,$reports->vehicle_no,$reports->chassis_no,$reports->engine_no,$vehicle_type,date('d-M-Y',strtotime($reports->registration_date)),date('d-M-Y',strtotime($reports->registration_exp_date)),date('d-M-Y',strtotime($reports->created_date)),$reports->charge);
                    $count = count($rowData);
                    for($j=0;$j<$count;$j++)
                    {
                       
                         $data[$i][$fields[$j]] = $rowData[$j];
                    }
                    unset($rowData);
                    $rowData = array();
                    $i++;  
                    $m++;
               }
             
          }

          // Fetching the table data
          $row = 2;
          if(!empty($data))
          {
               foreach($data as $boking_data)
               {
                    $col = 0;
                    foreach ($fields as $field)
                    { 
                         $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $boking_data[$field]);
                         $col++;
                    }
                    $row++;
               }
               $objPHPExcel->setActiveSheetIndex(0);
               $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
          }
          
          // Sending headers to force the user to download the file
          //header('Content-Type: application/octet-stream');
          header('Content-Type: application/vnd.ms-excel charset=UTF-8');
          header("Content-Disposition: attachment; filename=vehicle_list_".time().".xls");
          header("Pragma: no-cache"); 
          header("Expires: 0");
         if(!empty($data))
         {
          ob_end_clean();
          $objWriter->save('php://output');
         }
    }

    public function pdf_vehicle()
    {    
        $data['print_status']="";
        $data['data_list'] = $this->vehicle->search_report_data();
        //echo "<pre>";print_r($data['data_list']);die;
        $this->load->view('ambulance/vehicle/vehicle_report_html',$data);
        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        //echo $html; exit;
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("vehicle_list_".time().".pdf");
    }


     public function add_type()
    {
        
        $data['page_title'] = "Add type";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'relation'=>"",
                                  'status'=>"1"
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validatetype();
            if($this->form_validation->run() == TRUE)
            {
                $this->vehicle->save_type();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('ambulance/vehicle/type_add',$data);       
    }

      private function _validatetype()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('type', 'type', 'trim|required'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'type'=>$post['type'], 
                                        'status'=>$post['status']
                                       ); 
            return $data['form_data'];
        }   
    }
    
    
    // documents
public function upload_document($vehicle_id='')
{ 
    $this->load->model('ambulance/document_file_model','document_file');
    $data['page_title'] = 'Upload Document';   
    $data['form_error'] = [];
    $data['document_files_error'] = [];
    $data['document_list'] = $this->document_file->document_list();
     $data['vehicle_id'] = $vehicle_id;
    $post = $this->input->post();
  // print_r($post);die();
    $data['form_data'] = array(
                                 'data_id'=>'',
                                 'vehicle_id'=>$vehicle_id,
                                 'renewal_date'=>'',
                                 'expiry_date'=>'',
                                 'old_document_file'=>''
                              );
    if(isset($post) && !empty($post))
    {   
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>'); 
        $this->form_validation->set_rules('document_id', 'Document', 'trim|required'); 
        $this->form_validation->set_rules('renewal_date', 'Eenewal Date', 'trim|required'); 
        $this->form_validation->set_rules('expiry_date', 'Expiry Date', 'trim|required'); 
        if(!isset($_FILES['document_file']) || empty($_FILES['document_file']['name']))
        {
          $this->form_validation->set_rules('document_file', 'Document file', 'trim|required');  
        }
        if($this->form_validation->run() == TRUE)
        {
             $config['upload_path']   = DIR_UPLOAD_PATH.'vehicle_docs/';  
             $config['allowed_types'] = 'pdf|jpeg|jpg|png|doc|docx'; 
             $config['max_size']      = 0; 
             $config['encrypt_name'] = TRUE; 
             $this->load->library('upload', $config);
             
             if ($this->upload->do_upload('document_file')) 
             {
                $file_data = $this->upload->data(); 
                $this->document_file->save_file($file_data['file_name']);
                echo json_encode(array('result'=>1));
                exit();
              } 
             else
              { 
                $data['document_files_error'] = $this->upload->display_errors();
                $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'vehicle_id'=>$post['vehicle_id'],
                                        'document_id'=>$post['document_id'],
                                        'renewal_date'=>$post['renewal_date'],
                                        'expiry_date'=>$post['expiry_date'],
                                        'old_document_file'=>$post['old_document_file']
                                       );
              }   
        }
        else
        {         
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'vehicle_id'=>$post['vehicle_id'],
                                        'document_id'=>$post['document_id'],
                                        'renewal_date'=>$post['renewal_date'],
                                        'expiry_date'=>$post['expiry_date'], 
                                        'old_document_file'=>$post['old_document_file']
                                       );
            $data['form_error'] = validation_errors();  
            echo "<pre>"; print_r($data['form_error']); die;
        }   
    }
    $this->load->view('ambulance/vehicle/add_file',$data);
 }

  public function view_files($vehicle_id='')
  {
      $this->load->model('ambulance/document_file_model','document_file');
      $data['page_title'] = "Vehicle Document Files";
      $data['document_list'] = $this->document_file->document_list();
      $data['vehicle_id'] = $vehicle_id;
      $result = $this->vehicle->get_by_id($vehicle_id); 
      $data['vehicle_no'] = $result['vehicle_no'];
                               
      $this->load->view('ambulance/vehicle/view_files',$data);

  }

public function ajax_file_list($vehicle_id='')
{ 
       $this->load->model('ambulance/document_file_model','document_file');
        $users_data = $this->session->userdata('auth_users');
        $list = $this->document_file->get_datatables($vehicle_id); 
      //  print_r($list);die(); 
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $prescription) { 
            $no++;
            $row = array();
            if($prescription->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            }
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
            $row[] = '<input type="checkbox" name="document[]" class="checklist" value="'.$prescription->id.'">'.$check_script;

            $sign_img = "";
            if(!empty($prescription->vehicle_docs) && file_exists(DIR_UPLOAD_PATH.'vehicle_docs/'.$prescription->vehicle_docs))
            {
                $sign_img = ROOT_UPLOADS_PATH.'vehicle_docs/'.$prescription->vehicle_docs;
                $ext = pathinfo($sign_img, PATHINFO_EXTENSION);
                if($ext=='pdf' || $ext=='doc' || $ext=='docs')
                {
                  $sign_img = '<a href="'.$sign_img.'" download><img src="'.base_url().'assets/images/pdf.png'.'" width="70px"/></a>';
                }
                else{                  
                   $sign_img = '<a href="'.$sign_img.'" download><img src="'.$sign_img.'" width="70px"/></a>';
                }
            }

            $row[] = $sign_img;
            $row[] = $prescription->document; 
            $row[] = date('d-m-Y',strtotime($prescription->renewal_date)); 
            $row[] = date('d-m-Y',strtotime($prescription->expiry_date)); 
            $row[] = $status; 
            $row[] = $prescription->remarks;  
             $btn_del='';
             if(in_array('2459',$users_data['permission']['action'])){
              $btn_del = '<a class="btn-custom" onClick="return delete_document_file('.$prescription->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';  
             }
            $row[] = $btn_del;
            
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->document_file->count_all($vehicle_id),
                        "recordsFiltered" => $this->document_file->count_filtered($vehicle_id),
                        "data" => $data,
                );
        echo json_encode($output);
    }

    public function delete_document_file($id="")
    {
        $this->load->model('ambulance/document_file_model','document_file');
       if(!empty($id) && $id>0)
       {
           $result = $this->document_file->delete($id);
           $response = "Document successfully deleted.";
           echo $response;
       }
    }

    function deleteall_document_file()
    {
        $this->load->model('ambulance/document_file_model','document_file');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->document_file->deleteall($post['row_id']);
            $response = "Document successfully deleted.";
            echo $response;
        }
    }
    
        /* 25-04-2020 */
    public function advance_search()
    {
        $this->load->model('general/general_model'); 
        $data['page_title'] = "Advance Search";
        $post = $this->input->post();
      //  print_r($post);die;
        $data['location_list'] = $this->general_model->location_list();
        $data['owner_list'] = $this->general_model->amb_vendor_list();
        $data['type_list'] = $this->general_model->type_list();
       

        $data['form_data'] = array(
                                    "start_date"=>'',
                                    "end_date"=>'',
                                    'patient_name'=>'',
                                    "vehicle_no"=>"",
                                    'location'=>'',
                                    'chassis_no'=>'',
                                    'engine_no'=>'',
                                    'type'=>'',
                                    'vehicle_type'=>'',
                                    'reg_exp'=>'',
                                    'reg_date'=>'',
                                    'owner_name'=>'',
                                
                                  );
                         //  print_r($data['form_data']);die;       
        if(isset($post) && !empty($post))
        {
            $marge_post = array_merge($data['form_data'],$post);
            $this->session->set_userdata('vehicle_search', $marge_post);

        }
        $vehicle_search = $this->session->userdata('vehicle_search');
        if(isset($vehicle_search) && !empty($vehicle_search))
        {
            $data['form_data'] = $vehicle_search;
        }
        $this->load->view('ambulance/vehicle/advance_search',$data);
    }

      public function reset_search()
    {
        $this->session->unset_userdata('vehicle_search');

    }
    
    
  function check_unique_value($duration) 
  {     
        $users_data = $this->session->userdata('auth_users');
        $result = $this->vehicle->check_unique_value($users_data['parent_id'], $duration);
        if($result == 0)
            $response = true;
        else {
            $this->form_validation->set_message('check_unique_value', 'Vehicle No. already exist.');
            $response = false;
        }
        return $response;
    }
    /*25-04-2020*/

}
