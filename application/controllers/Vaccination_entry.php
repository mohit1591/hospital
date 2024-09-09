<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vaccination_entry extends CI_Controller {
 
	function __construct() 
	{
		parent::__construct();	
		auth_users();  
		$this->load->model('vaccination_entry/vaccination_entry_model','vaccination_entry');
		$this->load->library('form_validation');
    }

    
	public function index()
    {
       unauthorise_permission(183,1052);
        $data['page_title'] = 'Vaccination entry List'; 
        $data['manuf_company_list'] = $this->vaccination_entry->manuf_company_list();
        $this->session->unset_userdata('vaccination_entry_search');

        // Default Search Setting
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
        $data['form_data']=array('start_date'=>$start_date,'vaccination_name'=>'','end_date'=>$end_date,'vaccination_company'=>'');
        $this->load->view('vaccination_entry/list',$data);
    }

    public function ajax_list()
    {  
        unauthorise_permission(183,1052);
        $list = $this->vaccination_entry->get_datatables();  
        //print_r( $list);
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        //$row='';
        foreach ($list as $vaccination_entry) { 
            $no++;
            $row = array();
            if($vaccination_entry->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            }
            ///// State name ////////
            $state = "";
            if(!empty($vaccination_entry->state))
            {
                $state = " ( ".ucfirst(strtolower($vaccination_entry->state))." )";
            }
            //////////////////////// 
            
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
           // $doctor_type = array('0'=>'Referral','1'=>'Attended','2'=>'Referral/Attended');
            $row[] = '<input type="checkbox" name="vaccination_entry[]" class="checklist" value="'.$vaccination_entry->id.'">';
            $rack_name= rack_list($vaccination_entry->rack_no);
            $row[] = $vaccination_entry->vaccination_code;
            $row[] = $vaccination_entry->vaccination_name;
            $row[] = $vaccination_entry->company_name;
            $row[] = $vaccination_entry->packing;
            $row[] = $vaccination_entry->rack_no;
            $row[] = $vaccination_entry->mrp;
            $row[] = $vaccination_entry->purchase_rate;
           // $row[] = $vaccination_entry->discount;
            $row[] = $status; 
            $users_data = $this->session->userdata('auth_users');
            $btnedit='';
            $btndelete='';
            $btnview = '';
           if(in_array('1054',$users_data['permission']['action'])){
                 $btnedit = ' <a onClick="return edit_vaccination_entry('.$vaccination_entry->id.');" class="btn-custom" href="javascript:void(0)" style="'.$vaccination_entry->id.'" title="Edit"><i class="fa fa-pencil"></i> Edit</a>';
            }
            if(in_array('1059',$users_data['permission']['action'])){
                $btnview=' <a class="btn-custom" onclick="return view_vaccination_entry('.$vaccination_entry->id.')" href="javascript:void(0)" title="View"><i class="fa fa-info-circle"></i> View </a>';
           }
            if(in_array('1055',$users_data['permission']['action'])){
                $btndelete = ' <a class="btn-custom" onClick="return delete_vaccination_entry('.$vaccination_entry->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';
            }       
            $row[] = $btnedit.$btnview.$btndelete;
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->vaccination_entry->count_all(),
                        "recordsFiltered" => $this->vaccination_entry->count_filtered(),
                        "data" => $data,
                );
        //print_r($output);
        //output to json format
        echo json_encode($output);
    }

     public function vaccination_entry_excel()
    {
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          // Field names in the first row
          $fields = array('Vaccination Code','Vaccination Name','Vaccination company','Packing','Rack No.','MRP','Purchase Rate');
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
          $list = $this->vaccination_entry->search_report_data();
          $rowData = array();
          $data= array();
          if(!empty($list))
          {
               
               $i=0;
               foreach($list as $reports)
               {
                    
                    array_push($rowData,$reports->vaccination_code,$reports->vaccination_name,$reports->company_name,$reports->packing,$reports->rack_no,$reports->mrp,$reports->purchase_rate);
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
          header("Content-Disposition: attachment; filename=vaccination_report_".time().".xls");
          header("Pragma: no-cache"); 
          header("Expires: 0");
         if(!empty($data))
         {
          ob_end_clean();
          $objWriter->save('php://output');
         }
    }

    public function vaccination_entry_csv()
    {
          // Starting the PHPExcel library
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          // Field names in the first row
          $fields = array('Vaccination Code','Vaccination Name','Vaccination company','Packing','Rack No.','MRP','Purchase Rate');
          $col = 0;
          foreach ($fields as $field)
          {
               $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
               $col++;
          }
          $list = $this->vaccination_entry->search_report_data();
          $rowData = array();
          $data= array();
          if(!empty($list))
          {
              
               $i=0;
               foreach($list as $reports)
               {
                    
                    array_push($rowData,$reports->vaccination_code,$reports->vaccination_name,$reports->company_name,$reports->packing,$reports->rack_no,$reports->mrp,$reports->purchase_rate);
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
               foreach($data as $reports_data)
               {
                    $col = 0;
                    foreach ($fields as $field)
                    { 
                         $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $reports_data[$field]);
                         $col++;
                    }
                    $row++;
               }
               $objPHPExcel->setActiveSheetIndex(0);
               $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
          }
          
          // Sending headers to force the user to download the file
          header('Content-Type: application/octet-stream charset=UTF-8');
          header("Content-Disposition: attachment; filename=vaccination_report_".time().".csv");
          header("Pragma: no-cache"); 
          header("Expires: 0");
         if(!empty($data))
         {
          ob_end_clean();
          $objWriter->save('php://output');
         }
    
    }

    public function pdf_vaccination_entry()
    {    
        $data['print_status']="";
        $data['data_list'] = $this->vaccination_entry->search_report_data();
        $this->load->view('vaccination_entry/vaccination_report_html',$data);
        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        //echo $html; exit;
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("vaccination_report_".time().".pdf");
    }
    public function print_medicine_entry()
    {    
      $data['print_status']="1";
      $data['data_list'] = $this->vaccination_entry->search_report_data();
      $this->load->view('vaccination_entry/vaccination_report_html',$data); 
    }
	public function add()
	{
    unauthorise_permission(183,1053);
		$this->load->model('general/general_model'); 
		$data['page_title'] = "Vaccination entry";
		$data['form_error'] = [];
		$data['unit_list'] = $this->vaccination_entry->unit_list();
		$data['rack_list'] = $this->vaccination_entry->rack_list();
		$data['manuf_company_list'] = $this->vaccination_entry->manuf_company_list(); 
		$reg_no = generate_unique_id(35);
  
        //echo $reg_no;die;
		$post = $this->input->post();
		$data['form_data'] = array(
                                    "data_id"=>"",
                                    "branch_id"=>"",
                                    "vaccination_code"=>$reg_no,
                                    "vaccination_name"=>"",
                                    "unit_id"=>"",
                                    "unit_second_id"=>"",
                                    "conversion"=>"",
                                    "min_alrt"=>"",
                                    "packing"=>"",
                                    "rack_no"=>"",
                                    "hsn_no"=>"",
                                    "salt"=>"",
                                    "manuf_company"=>"",
                                    "mrp"=>"",
                                    "purchase_rate"=>"",
                                    "discount"=>"",
                                    'cgst'=>"",
                                    'sgst'=>"",
                                    'igst'=>"",
                                    //"vat"=>"",
                                    "status"=>"1", 
			                      );
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->vaccination_entry->save();
                echo 1;
                return false; 
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }       
        }
        $this->session->unset_userdata('comission_data');
		$this->load->view('vaccination_entry/add',$data);
	}
        public function reset_search()
        {
            $this->session->unset_userdata('vaccination_entry_search');
        }
     public function advance_search()
      {
        
          $this->load->model('general/general_model'); 
          $data['page_title'] = "Advance Search";
          $post = $this->input->post();
          $data['simulation_list'] = $this->general_model->simulation_list();
          $data['doctors_list']= $this->general_model->doctors_list();
          $data['unit_list'] = $this->vaccination_entry->unit_list();
          $data['form_data'] = array(
                                      "start_date"=>"",
                                      "end_date"=>"",
                                      "vaccination_name"=>"",
                                      "unit1"=>"",
                                      "unit2"=>"",
                                      "rack_no"=>"",
                                      "cgst"=>"",
                                      "sgst"=>"",
                                      "igst"=>"",
                                      "packing"=>"",
                                      "mrp_to"=>"",
                                      "mrp_from"=>"",
                                      "vaccination_company"=>"",
                                      "vaccination_code"=>"",
                                      "purchase_to"=>"",
                                      "purchase_from"=>"",
                                      "discount"=>"",
                                      "hsn_no"=>"",
                                      //"vat"=>"",
                                      "min_alert"=>"",
                                      "conversion"=>"",
                                      "end_date"=>""
                                    );
          if(isset($post) && !empty($post))
          {
            //print_r($post);die;
              $marge_post = array_merge($data['form_data'],$post);
              $this->session->set_userdata('vaccination_entry_search', $marge_post);
          }
          $vaccination_entry_search = $this->session->userdata('vaccination_entry_search');
          if(isset($vaccination_entry_search) && !empty($vaccination_entry_search))
          {
              $data['form_data'] = $vaccination_entry_search;
          }
          $this->load->view('vaccination_entry/advance_search',$data);
   }

	public function edit($id="")
    {
         unauthorise_permission(183,1054);
     if(isset($id) && !empty($id) && is_numeric($id))
      {       
        $result = $this->vaccination_entry->get_by_id($id); 
        //print_r($result);die;
        //$reg_no = generate_unique_id(10);
        $this->load->model('general/general_model');
    	$data['unit_list'] = $this->vaccination_entry->unit_list();
		$data['rack_list'] = $this->vaccination_entry->rack_list();
		$data['manuf_company_list'] = $this->vaccination_entry->manuf_company_list();
        $data['page_title'] = "Update Vaccination entry";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                    "data_id"=>$result['id'], 
                                    "vaccination_code"=>$result['vaccination_code'],
                                    "vaccination_name"=>$result['vaccination_name'],
                                    "unit_id"=>$result['unit_id'],
                                    "unit_second_id"=>$result['unit_second_id'],
                                    "conversion"=>$result['conversion'],
                                    "min_alrt"=>$result['min_alrt'],
                                    "packing"=>$result['packing'],
                                    "rack_no"=>$result['rack_no'],
                                    "salt"=>$result['salt'],
                                    "manuf_company"=>$result['manuf_company'],
                                    "mrp"=>$result['mrp'],
                                    "purchase_rate"=>$result['purchase_rate'],
                                    'cgst'=>$result['cgst'],
                                    'sgst'=>$result['sgst'],
                                    'igst'=>$result['igst'],
                                    "hsn_no"=>$result['hsn_no'],
                                    //"vat"=>$result['vat'],
                                    "discount"=>$result['discount'],
                                    "status"=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->vaccination_entry->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }

       $this->session->unset_userdata('comission_data'); 
       $this->load->view('vaccination_entry/add',$data);       
      }
    }
     
    private function _validate()
    {
         $field_list = mandatory_section_field_list(5);
         $users_data = $this->session->userdata('auth_users');  
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('vaccination_name', 'vaccination name', 'trim|required');
        $this->form_validation->set_rules('unit_id', 'unit', 'trim|required'); 
        $this->form_validation->set_rules('unit_second_id', 'unit 2nd.', 'trim|required'); 
        $this->form_validation->set_rules('conversion', 'conversion', 'trim|required');
         //$this->form_validation->set_rules('manuf_company', 'company name', 'trim|required');
          if(!empty($field_list)){
            
           if($field_list[0]['mandatory_field_id']=='29' && $field_list[0]['mandatory_branch_id']==$users_data['parent_id']){
                  
                    $this->form_validation->set_rules('purchase_rate', 'purchase rate', 'trim|required|numeric|callback_check_purchase_rate');
                 // $this->form_validation->set_rules('purchase_rate', 'purchase rate', 'trim|required|numeric'); 
              }
              else
              {
              $this->form_validation->set_rules('purchase_rate', 'purchase rate', 'callback_check_purchase_rate');
              }
           
           
        }
       
       
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(10); 
            $data['form_data'] = array(
                                    "data_id"=>$_POST['data_id'], 
                                    "vaccination_code"=>$reg_no,
                                    "vaccination_name"=>$_POST['vaccination_name'],
                                    "unit_id"=>$_POST['unit_id'],
                                    "unit_second_id"=>$_POST['unit_second_id'],
                                    "conversion"=>$_POST['conversion'],
                                    "min_alrt"=>$_POST['min_alrt'],
                                    "packing"=>$_POST['packing'],
                                    "rack_no"=>$_POST['rack_no'],
                                    "salt"=>$_POST['salt'],
                                    "manuf_company"=>$_POST['manuf_company'],
                                    "mrp"=>$_POST['mrp'],
                                    "purchase_rate"=>$_POST['purchase_rate'],
                                    //"vat"=>$_POST['vat'],
                                    "discount"=>$_POST['discount'],
                                    "hsn_no"=>$_POST['hsn_no'],
                                    'cgst'=>$_POST['cgst'],
                                    'sgst'=>$_POST['sgst'],
                                    'igst'=>$_POST['igst'],
                                    "status"=>$_POST['status']
                                  );  
            return $data['form_data'];
        }   
    }

  public function check_purchase_rate($str)
    {

      $post = $this->input->post();
      if(!empty($str))
      {
          $this->load->model('general/general_model','general'); 
          if(!empty($post['data_id']) && $post['data_id']>0)
          {
                
             if(!empty($post['purchase_rate']) && $post['purchase_rate']>$post['mrp'])
                  {
                     $this->form_validation->set_message('check_purchase_rate', 'Purchase rate must be less and equal to MRP');
                   return false;
                  }
                  else
                  {
                     return true;
                  }
          }
          else
          {
                 if(!empty($post['purchase_rate']) && $post['purchase_rate']>$post['mrp'])
                  {
                     $this->form_validation->set_message('check_purchase_rate', 'Purchase rate must be less and equal to MRP');
                   return false;
                  }
                  else
                  {
                     return true;
                  }
          }  
      }
      // else
      // {
      //   $this->form_validation->set_message('check_purchase_rate', 'Purchase rate field is required.');
      //         return false; 
      // } 
    }
 
    public function delete($id="")
    {
        unauthorise_permission(183,1055);
       if(!empty($id) && $id>0)
       {
           $result = $this->vaccination_entry->delete($id);
           $response = "Vaccination entry successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission(183,1055);
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->vaccination_entry->deleteall($post['row_id']);
            $response = "Vaccination entry successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
        unauthorise_permission(183,1059);
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->vaccination_entry->get_by_id($id);  
        $data['page_title'] = $data['form_data']['vaccination_name']." detail";
        $this->load->view('vaccination_entry/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
       unauthorise_permission(183,1058);
        $data['page_title'] = 'Vaccination entry archive list';
        $this->load->helper('url');
        $this->load->view('vaccination_entry/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission(183,1058);
        $this->load->model('vaccination_entry/vaccination_entry_archive_model','vaccination_entry_archive'); 

        $list = $this->vaccination_entry_archive->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $vaccination_entry) { 
            $no++;
            $row = array();
            if($vaccination_entry->status==1)
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

            $row[] = '<input type="checkbox" name="vaccination_entry[]" class="checklist" value="'.$vaccination_entry->id.'">';
            $rack_name= rack_list($vaccination_entry->rack_no);
           $row[] = $vaccination_entry->vaccination_code;
            $row[] = $vaccination_entry->vaccination_name;
            $row[] = $vaccination_entry->company_name;
            $row[] = $vaccination_entry->packing;
            $row[] = $vaccination_entry->rack_no;
            $row[] = $vaccination_entry->mrp;
            $row[] = $vaccination_entry->purchase_rate;
            $row[] = $status; 
            $row[] = date('d-M-Y H:i A',strtotime($vaccination_entry->created_date));  
            $users_data = $this->session->userdata('auth_users');
            $btnrestore='';
            $btndelete='';
            
           if(in_array('373',$users_data['permission']['action'])){
                $btnrestore = ' <a onClick="return restore_vaccination_entry('.$vaccination_entry->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
           }
           if(in_array('372',$users_data['permission']['action'])){
                $btndelete = ' <a onClick="return trash('.$vaccination_entry->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>';
           }
            $row[] = $btnrestore.$btndelete; 
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->vaccination_entry_archive->count_all(),
                        "recordsFiltered" => $this->vaccination_entry_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
       unauthorise_permission(183,1057);
        $this->load->model('vaccination_entry/vaccination_entry_archive_model','vaccination_entry_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->vaccination_entry_archive->restore($id);
           $response = "Vaccination entry successfully restore in vaccination entry list.";
           echo $response;
       }
    }

    function restoreall()
    { 
       unauthorise_permission(183,1057);
        $this->load->model('vaccination_entry/vaccination_entry_archive_model','vaccination_entry_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->vaccination_entry_archive->restoreall($post['row_id']);
            $response = "Vaccination entry successfully restore in vaccination entry list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission(183,1056);
        $this->load->model('vaccination_entry/vaccination_entry_archive_model','vaccination_entry_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->vaccination_entry_archive->trash($id);
           $response = "Vaccination entry successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
       unauthorise_permission(183,1056);
        $this->load->model('vaccination_entry/vaccination_entry_archive_model','vaccination_entry_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->vaccination_entry_archive->trashall($post['row_id']);
            $response = "Vaccination entry successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function vaccination_dropdown()
  {
      $medicine_entry_list = $this->vaccination_entry->employee_type_list();
      $dropdown = '<option value="">Select VaccinatiOn entry</option>'; 
      if(!empty($medicine_entry_list))
      {
        foreach($medicine_entry_list as $vaccination_entry)
        {
           $dropdown .= '<option value="'.$vaccination_entry->id.'">'.$vaccination_entry->vaccination_name.'</option>';
        }
      } 
      echo $dropdown; 
  }
  
  // this function is used for sample excel sheet doctor list on 23-04-2018
      public function sample_import_vaccination_excel()
    {
            //unauthorise_permission(97,627);
            // Starting the PHPExcel library
        
            $this->load->library('excel');
            $this->excel->IO_factory();
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
            $objPHPExcel->setActiveSheetIndex(0);
            // Field names in the first row
            $fields = array('Vaccination Name','Mfg.Company','Unit 1(*)','Conversion*','Packing','Salt','MRP','Discount (%)','CGST (%)','SGST (%)','IGST (%)','Unit 2*','Min.Alrt','Rack No.','Purchase Rate','HSN No.');

            
      
              $col = 0;
            foreach ($fields as $field)
            {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
                $col++;
            }
            $rowData = array();
            $data= array();
          
            // Fetching the table data
            $objPHPExcel->setActiveSheetIndex(0);
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
            // Sending headers to force the user to download the file
            header('Content-Type: application/vnd.ms-excel charset=UTF-8');
            header("Content-Disposition: attachment; filename=vaccination_entry_import_sample_".time().".xls");  
            header("Pragma: no-cache"); 
            header("Expires: 0");
            ob_end_clean();
            $objWriter->save('php://output');
           
    }

    // this function is used for import excel sheet doctor list on 23-04-2018
     public function import_vaccination_excel()
    {
        //unauthorise_permission(97,628);
        $this->load->library('excel');
        $data['page_title'] = 'Import Vaccination excel';
        $arr_data = array();
        $header = array();
        $path='';

      //echo"hello";print_r($_FILES); 
        if(isset($_FILES) && !empty($_FILES))
        {
          
            $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
            if(!isset($_FILES['vaccination_list']) || $_FILES['vaccination_list']['error']>0)
            {
               
               $this->form_validation->set_rules('vaccination_list', 'file', 'trim|required');  
            } 
            $this->form_validation->set_rules('name', 'name', 'trim|required');  
            if($this->form_validation->run() == TRUE)
            {
               
                $config['upload_path']   = DIR_UPLOAD_PATH.'patients/'; 
                $config['allowed_types'] = '*'; 
                $this->load->library('upload', $config);
                if(!$this->upload->do_upload('vaccination_list')) 
                {
                    $error = array('error' => $this->upload->display_errors()); 
                    $data['file_upload_eror'] = $error; 

                    //echo "<pre> dfd";print_r(DIR_UPLOAD_PATH.'patient/'); exit;
                }
                else 
                { 
                    $data = $this->upload->data();
                    $path = $config['upload_path'].$data['file_name'];

                    //read file from path
                    $objPHPExcel = PHPExcel_IOFactory::load($path);
                    //get only the Cell Collection
                    $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
                    //extract to a PHP readable array format
                    foreach ($cell_collection as $cell) 
                    {
                        $column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
                        $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
                        $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
                        //header will/should be in row 1 only. of course this can be modified to suit your need.
                        if ($row == 1) 
                        {
                            $header[$row][$column] = $data_value;
                        } 
                        else 
                        {
                            $arr_data[$row][$column] = $data_value;
                        }
                    }
                    //send the data in an array format
                    $data['header'] = $header;
                    $data['values'] = $arr_data;
                   
                }

                //echo "<pre>";print_r($arr_data); exit;
                if(!empty($arr_data))
                {
                    //echo '<pre>'; print_r($arr_data); exit;
                    $arrs_data = array_values($arr_data);
                    $total_vaccination = count($arrs_data);
                    
                   $array_keys = array('vaccination_name','manuf_company','unit_id','conversion','packing','salt','mrp','discount','cgst','sgst','igst','unit_second_id','min_alrt','rack_no','purchase_rate','hsn_no');

                    $count_array_keys = count($array_keys);
                    $array_values_keys = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P');
                    $vaccination_data = array();
                    
                    $j=0;
                    $m=0;
                    $data='';
                    $vaccination_all_data= array();
                    for($i=0;$i<$total_vaccination;$i++)
                    {
                        $med_data_count_values[$i] = count($arrs_data[$i]);
                        for($p=0;$p<$count_array_keys;$p++)
                        {
                            if(array_key_exists($array_values_keys[$p],$arrs_data[$i]))
                            {
                                $vaccination_all_data[$i][$array_keys[$p]] = $arrs_data[$i][$array_values_keys[$p]];
                            }
                            else
                            {
                                $vaccination_all_data[$i][$array_keys[$p]] ="";
                            }
                        }
                    }

                   // echo '<pre>'; print_r($doctor_all_data); exit;
                    $this->vaccination_entry->save_all_vaccination($vaccination_all_data);
                }
                if(!empty($path))
                {
                    unlink($path);
                }
               
                echo 1;
                return false;
            }
            else
            {

                $data['form_error'] = validation_errors();
                

            }
        }

        $this->load->view('vaccination_entry/import_vaccination_excel',$data);
    } 




}
