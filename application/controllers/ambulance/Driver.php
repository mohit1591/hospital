<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Driver extends CI_Controller {
 
	function __construct() 
	{
		parent::__construct();	
		auth_users();  
        $this->load->model('general/general_model','general');
        $this->load->model('ambulance/driver_model','driver');
		 $this->load->library('form_validation');
    }

    
	public function index()
    {
       unauthorise_permission('347','2076');
        $data['page_title'] = 'Driver List'; 
        $this->load->view('ambulance/driver/list',$data);
    }

     public function ajax_list()
    { 
      unauthorise_permission('347','2076');
      $users_data = $this->session->userdata('auth_users');
      $sub_branch_details = $this->session->userdata('sub_branches_data');
      $parent_branch_details = $this->session->userdata('parent_branches_data');
      $list = $this->driver->get_datatables();
       // print_r($list);die;
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $driver_list) {
         // print_r($ipd_perticular);die;
            $no++;
            $row = array();
            if($driver_list->status==1)
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
            if($users_data['parent_id']==$driver_list->branch_id)
            {
               $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$driver_list->id.'">'.$check_script;
            }else{
               $row[]='';
            }
            $row[] = $driver_list->driver_name;
            $row[] = $driver_list->licence_no;
            $row[] = $driver_list->mobile_no;
            $row[] = $driver_list->address;
            // $row[] = $status;
            // $row[] = date('d-M-Y H:i A',strtotime($ipd_perticular->created_date)); 
            $btnedit='';
            $btndelete='';
          
            if($users_data['parent_id']==$driver_list->branch_id)
            {
              if(in_array('2078',$users_data['permission']['action'])){
               $btnedit =' <a onClick="return edit_driver_list('.$driver_list->id.');" class="btn-custom" href="javascript:void(0)" style="'.$driver_list->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
              }
             if(in_array('2079',$users_data['permission']['action'])){
                    $btndelete = ' <a class="btn-custom" onClick="return delete_driver_list('.$driver_list->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';   
                }
            }
          
             $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                       "recordsTotal" => $this->driver->count_all(),
                        "recordsFiltered" => $this->driver->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
 

      public function add()
    {
        unauthorise_permission('347','2077');
        $data['page_title'] = 'Add Driver'; 
        $this->load->model('general/general_model'); 
         $data['country_list'] = $this->general_model->country_list();
         $data['relation_list'] = $this->general_model->relation_list();
          $data['gardian_relation_list'] = $this->general_model->gardian_relation_list();
      
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                'driver_name'=>"",
                                'licence_no'=>"",
                                'mobile_no'=>"",
                                'email'=>"",
                                'dob'=>"",
                                'guardian_name'=>"",
                                'gaurdian_mob'=>"",
                                'relation'=>"",
                                'address'=>"",
                                'country_id'=>"99",
                                'state_id'=>"",
                                'city_id'=>"",
                                'pincode'=>"",
                                'dl_expiry_date'=>"",
                        
                                  );   


        if(isset($post) && !empty($post))
        {   
          
         $data['form_data'] = $this->_validate();
             if($this->form_validation->run() == TRUE)
             {
                $this->driver->save();
                echo 1;
                return false;
                
            }
            else
            {

                $data['form_error'] = validation_errors();  
             
            }
        }
       $this->load->view('ambulance/driver/add',$data);       
    }

     public function edit($id="")
    {
     unauthorise_permission('347','2078');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->driver->get_by_id($id);
        $this->load->model('general/general_model');   
          $data['country_list'] = $this->general_model->country_list();
          $data['relation_list'] = $this->general_model->relation_list();
        $data['page_title'] = "Edit Driver details";  
        $post = $this->input->post();
        $dob=date('d-m-Y',strtotime($result['dob']));
        $data['form_error'] = ''; 
         $data['form_data'] = array(
                                  'data_id'=>$result['id'], 
                                'driver_name'=>$result['driver_name'],
                                'licence_no'=>$result['licence_no'],
                                'mobile_no'=>$result['mobile_no'],
                                'email'=>$result['email'],
                                'dob'=>$dob,
                                'guardian_name'=>$result['guardian_name'],
                                'gaurdian_mob'=>$result['gaurdian_mob'],
                                'relation'=>$result['relation'],
                                'address'=>$result['address'],
                                'country_id'=>$result['country'],
                                'state_id'=>$result['state'],
                                'city_id'=>$result['city'],
                                'pincode'=>$result['pincode'],
                                'dl_expiry_date'=>date('d-m-Y',strtotime($result['dl_expiry_date'])),
                                  );   
       
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->driver->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('ambulance/driver/add',$data);       
      }
    }
      private function _validate()
    {
        $post = $this->input->post();  
      //  print_r($post);die;
      
        $this->load->model('general/general_model'); 
      
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('driver_name', 'Drive name', 'trim|required'); 
        $this->form_validation->set_rules('licence_no', 'DL No', 'trim|required'); 
       
         $this->form_validation->set_rules('mobile_no', 'Mobile No', 'trim|required|numeric'); 
      

          
        if ($this->form_validation->run() == FALSE) 
        {  
           // echo 'kl';die;
           // $reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                         'data_id'=>$post['data_id'], 
                                'driver_name'=>$post['driver_name'],
                                'lisence_no'=>$post['lisence_no'],
                                'mobile_no'=>$post['mobile_no'],
                                'email'=>$post['email'],
                                'dob'=>$post['dob'],
                                'guardian_name'=>$post['guardian_name'],
                                'gaurdian_mob'=>$post['gaurdian_mob'],
                                'relation'=>$post['relation'],
                                'address'=>$post['address'],
                                'country_id'=>"99",
                                'state_id'=>$post['state_id'],
                                'city_id'=>$post['city_id'],
                                'pincode'=>$post['pincode'],
                                       ); 
         
            //print '<pre>'; print_r($data['form_data']);die;
             return $data['form_data'];
            
        }
     //   return $data['form_data'];   
    }

    public function delete($id="")
    {
       unauthorise_permission('347','2079');
       if(!empty($id) && $id>0)
       {
           $result = $this->driver->delete($id);
           $response = "Driver successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission('347','2079');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->driver->deleteall($post['row_id']);
            $response = "Drivers successfully deleted.";
            echo $response;
        }
    }

    public function driver_excel()
    {
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          // Field names in the first row
          $fields = array('Driver Name','Mobile No','DL no','Address');
          $col = 0;
           $objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           
          foreach ($fields as $field)
          {
               $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
                 $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
               
                $col++;
          }
          $list = $this->driver->search_report_data();

          $rowData = array();
          $data= array();
          if(!empty($list))
          {
               
               $m=1;
               foreach($list as $reports)
               {
                    
                    array_push($rowData,$reports->driver_name,$reports->mobile_no,$reports->licence_no,$reports->address);
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
          header("Content-Disposition: attachment; filename=Driver_list_".time().".xls");
          header("Pragma: no-cache"); 
          header("Expires: 0");
         if(!empty($data))
         {
          ob_end_clean();
          $objWriter->save('php://output');
         }
    }

    public function pdf_driver()
    {    
        $data['print_status']="";
        $data['data_list'] = $this->driver->search_report_data();
        //echo "<pre>";print_r($data['data_list']);die;
        $this->load->view('ambulance/driver/driver_report_html',$data);
        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        //echo $html; exit;
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("driver_list_".time().".pdf");
    }

}
?>