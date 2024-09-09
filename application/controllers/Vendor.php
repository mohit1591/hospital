<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vendor extends CI_Controller {
 
	function __construct() 
	{
		parent::__construct();	
		auth_users();  
		$this->load->model('vendor/vendor_model','vendor');
		$this->load->library('form_validation');
    }

    
	public function index()
    {
       unauthorise_permission(57,375);
        $data['page_title'] = 'Vendor List'; 
        $this->load->view('vendor/list',$data);
    }

    public function ajax_list()
    {  
        unauthorise_permission(57,375);
        $list = $this->vendor->get_datatables();  
       
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        //$row='';
        foreach ($list as $vendor) { 
            $no++;
            $row = array();
            if($vendor->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            }

            if($vendor->vendor_type==1)
            {
            $vendor_type = 'Medicine';
            }   
            else if($vendor->vendor_type==2)
            {
            $vendor_type = 'Expense';
            }
            else if($vendor->vendor_type==3)
            {
            $vendor_type = 'Inventory';
            }
            else if($vendor->vendor_type==4)
            {
            $vendor_type = 'Vaccination';
            }
            else
            {
              $vendor_type='';
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
           // $doctor_type = array('0'=>'Referral','1'=>'Attended','2'=>'Referral/Attended');
            $row[] = '<input type="checkbox" name="vendor[]" class="checklist" value="'.$vendor->id.'">'.$check_script;
            $row[] = $vendor->name;
            $row[] = $vendor->vendor_id;
            $row[] = $vendor_type;
            $row[] = $vendor->mobile;
            $row[] = $vendor->email;
            $row[] = $status; 
            //$row[] = date('d-M-Y H:i A',strtotime($vendor->created_date));  
            $users_data = $this->session->userdata('auth_users');
            $btnedit='';
            $btndelete='';
            $btnview = '';
           if(in_array('377',$users_data['permission']['action'])){
                 $btnedit = ' <a onClick="return edit_vendor('.$vendor->id.');" class="btn-custom" href="javascript:void(0)" style="'.$vendor->id.'" title="Edit"><i class="fa fa-pencil"></i> Edit</a>';
            }
            if(in_array('382',$users_data['permission']['action'])){
                $btnview=' <a class="btn-custom" onclick="return view_vendor('.$vendor->id.')" href="javascript:void(0)" title="View"><i class="fa fa-info-circle"></i> View </a>';
           }
            if(in_array('378',$users_data['permission']['action'])){
                $btndelete = ' <a class="btn-custom" onClick="return delete_vendor('.$vendor->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';
               }
               if(in_array('383',$users_data['permission']['action'])){
                if($vendor->vendor_type==1){
                    
                    $btn_purchase = ' <a class="btn-custom" href="'.base_url('purchase_estimate/add/'.$vendor->id).'" style="'.$vendor->id.'" title="Estimate"><i class="fa fa-plus"></i> Estimate</a>';
                    
                    $btn_purchase .= '<a class="btn-custom" href="'.base_url('purchase/add/'.$vendor->id).'" style="'.$vendor->id.'" title="Purchase"><i class="fa fa-plus"></i> Purchase</a>';
                    
                    
                }
               
                else
                {
                  $btn_purchase='';
                }
              
               }
               if(in_array('384',$users_data['permission']['action'])){
               if($vendor->vendor_type==1){
                $btn_purchase_return = ' <a class="btn-custom" href="'.base_url('purchase_return/add/'.$vendor->id).'" style="'.$vendor->id.'" title="Return"> <i class="fa fa-plus"></i> Return</a>';
               }else{
                $btn_purchase_return="";
               }
                
              

             }       
            $row[] = $btnview.$btnedit.$btn_purchase.$btn_purchase_return.$btndelete;
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->vendor->count_all(),
                        "recordsFiltered" => $this->vendor->count_filtered(),
                        "data" => $data,
                );
        //print_r($output);
        //output to json format
        echo json_encode($output);
    }

	public function add($type='')
	{
       // unauthorise_permission(57,376);
		$this->load->model('general/general_model'); 
		$data['page_title'] = "Add Vendor";
		$data['form_error'] = [];
		$data['vendor_list'] = $this->vendor->vendor_list(); 
		$reg_no = generate_unique_id(11);
		$post = $this->input->post();
        $data['type']= $type;
		$data['form_data'] = array(
                                    "data_id"=>"",
                                    "branch_id"=>"",
                                    "vendor_id"=>$reg_no,
                                    "name"=>"",
                                    "vendor_gst"=>'',
                                    "mobile"=>"",
                                    "address"=>"",
                                    "address2"=>"",
                                    "address3"=>"",
                                    "vendor_type"=>'',
                                    'vendor_email'=>"",
                                    "status"=>"1", 
                                    "country_code"=>"+91"
			                      );
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->vendor->save();
                echo 1;
                return false; 
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }       
        }
       $this->load->view('vendor/add',$data);
	}

	public function edit($id="")
    {
         unauthorise_permission(57,377);
     if(isset($id) && !empty($id) && is_numeric($id))
     {       
        $result = $this->vendor->get_by_id($id); 
        //print_r($result);die;
        $reg_no = generate_unique_id(11);
        $this->load->model('general/general_model');
    	$data['vendor_list'] = $this->vendor->vendor_list();
        $data['page_title'] = "Update Vendor";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                    "data_id"=>$result['id'], 
                                    "vendor_id"=>$result['vendor_id'],
                                    'name'=>$result['name'],
                                    'vendor_gst'=>$result['vendor_gst'],
                                    'mobile'=>$result['mobile'],
                                    'vendor_email'=>$result['email'],
                                    "vendor_type"=>$result['vendor_type'],
                                    'address'=>$result['address'],
                                    'address2'=>$result['address2'],
                                    'address3'=>$result['address3'],
                                    "status"=>$result['status'],
                                     "country_code"=>"+91"
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->vendor->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
        $data['type']= $this->uri->segment(1);
       $this->load->view('vendor/add',$data);       
      }
    }
     
    private function _validate()
    {

        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('name', 'vendor name', 'trim|required');
        $this->form_validation->set_rules('vendor_type', 'vendor type', 'trim|required');
        $check_ids= $this->vendor->get_by_id($_POST['data_id']);
       if(!empty($_POST['data_id']) && $check_ids['email']==$_POST['vendor_email']){
        }else{
        $this->form_validation->set_rules('vendor_email', 'Email', 'trim|valid_email|is_unique[hms_medicine_vendors.email]');
        }
        //$this->form_validation->set_rules('unit_second_id', 'Unit2nd.', 'trim|required'); 
        //$this->form_validation->set_rules('conversion', 'Conversion', 'trim|required');
       // $this->form_validation->set_rules('purchase_rate', 'Purchase rate', 'trim|required|numeric'); 
        $this->form_validation->set_rules('vendor_type', 'Type', 'trim|required'); 
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(11);
            if(!empty($_POST['vendor_type']))
            {
              $vendor_type = $_POST['vendor_type'];
            } 
            else
            {
              $vendor_type = '';
            }
            $data['form_data'] = array(
                                    "data_id"=>$post['data_id'], 
                                    "vendor_id"=>$reg_no,
                                    "name"=>$post['name'],
                                    'vendor_gst'=>$post['vendor_gst'],
                                    "address"=>$post['address'],
                                    "address2"=>$post['address2'],
                                    "address3"=>$post['address3'],
                                    'vendor_email'=>$post['vendor_email'],
                                    "mobile"=>$post['mobile'],
                                    "vendor_type"=>$vendor_type,
                                    "status"=>$post['status'],
                                     "country_code"=>"+91"
                                  );  
            return $data['form_data'];
        }   
    }

    public function delete($id="")
    {
        unauthorise_permission(57,378);
       if(!empty($id) && $id>0)
       {
           $result = $this->vendor->delete($id);
           $response = "Vendor successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission(57,378);
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->vendor->deleteall($post['row_id']);
            $response = "Vendor successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
        unauthorise_permission(57,382);
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->vendor->get_by_id($id);  
        $data['page_title'] = $data['form_data']['name']." detail";
        $this->load->view('vendor/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
       unauthorise_permission(57,379);
        $data['page_title'] = 'Vendor Archive List';
        $this->load->helper('url');
        $this->load->view('vendor/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission(57,379);
        $this->load->model('vendor/vendor_archive_model','vendor_archive'); 

        $list = $this->vendor_archive->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $vendor_type='';
        $total_num = count($list);
        foreach ($list as $vendor) { 
            $no++;
            $row = array();
            if($vendor->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            }
           if($vendor->vendor_type==1)
            {
            $vendor_type = 'Medicine';
            }   
            else if($vendor->vendor_type==2)
            {
            $vendor_type = 'Expense';
            }
            else if($vendor->vendor_type==3)
            {
            $vendor_type = 'Inventory';
            }
            else if($vendor->vendor_type==4)
            {
            $vendor_type = 'Vaccination';
            }
            else
            {
              $vendor_type='';
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

            $row[] = '<input type="checkbox" name="vendor[]" class="checklist" value="'.$vendor->id.'">'.$check_script;
            $row[] = $vendor->name;
            $row[] = $vendor->vendor_id;
            $row[] = $vendor_type; 
            $row[] = $vendor->mobile;
            $row[] = $vendor->email;
            $row[] = $status; 
            //$row[] = date('d-M-Y H:i A',strtotime($vendor->created_date));  
            $users_data = $this->session->userdata('auth_users');
            $btnrestore='';
            $btndelete='';
            
           if(in_array('381',$users_data['permission']['action'])){
                $btnrestore = ' <a onClick="return restore_vendor('.$vendor->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
           }
           if(in_array('380',$users_data['permission']['action'])){
                $btndelete = ' <a onClick="return trash('.$vendor->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>';
           }
            $row[] = $btnrestore.$btndelete; 
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->vendor_archive->count_all(),
                        "recordsFiltered" => $this->vendor_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
       unauthorise_permission(57,381);
        $this->load->model('vendor/vendor_archive_model','vendor_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->vendor_archive->restore($id);
           $response = "Vendor successfully restore in vendor list.";
           echo $response;
       }
    }

    function restoreall()
    { 
       unauthorise_permission(57,381);
        $this->load->model('vendor/vendor_archive_model','vendor_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->vendor_archive->restoreall($post['row_id']);
            $response = "Vendor successfully restore in vendor list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission(57,380);
        $this->load->model('vendor/vendor_archive_model','vendor_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->vendor_archive->trash($id);
           $response = "Vendor successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
       unauthorise_permission(57,380);
        $this->load->model('vendor/vendor_archive_model','vendor_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->vendor_archive->trashall($post['row_id']);
            $response = "Vendor successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function vendor_dropdown($type="")
  {

      $vendor_list = $this->vendor->vendor_list($type);
      $dropdown = '<option value="">Select Vendor</option>'; 
      if(!empty($vendor_list))
      {
        foreach($vendor_list as $vendor)
        {
           $dropdown .= '<option value="'.$vendor->id.'">'.$vendor->name.'</option>';
        }
      } 
      echo $dropdown; 
  }
  
  public function vendor_excel()
    {
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          // Field names in the first row
          $fields = array('Vendor Name','Vendor Code','GST','Mobile No.','Email','Adress1','Address2','Address3');
          $col = 0;
           $objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('H')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          
          foreach ($fields as $field)
          {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
               
               
               $col++;
          }
          $list = $this->vendor->search_report_data();
          //echo "<pre>"; print_r($list); exit;
          $rowData = array();
          $data= array();
          if(!empty($list))
          {
               
               $i=0;
               foreach($list as $reports)
               {
                    
                    array_push($rowData,$reports->name,$reports->vendor_id,$reports->vendor_gst,$reports->mobile_no,$reports->email,$reports->address, $reports->address2,$reports->address3);
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
          header("Content-Disposition: attachment; filename=vendor_".time().".xls");
          header("Pragma: no-cache"); 
          header("Expires: 0");
         if(!empty($data))
         {
          ob_end_clean();
          $objWriter->save('php://output');
         }


    }



    public function pdf_vendor()
    {    
        /*$data['print_status']="";
        $data['data_list'] =$this->vendor->search_report_data();
        $this->load->view('vendor/vendor_report_html',$data);
        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        //echo $html; exit;
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("vendor_report_".time().".pdf");*/
        
        $data['print_status']="";
        $data['data_list'] =$this->vendor->search_report_data();
        $this->load->view('vendor/vendor_report_html',$data);
        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("vendor_report_".time().".pdf");
        
    }
    public function print_vendor()
    {    
      $data['print_status']="1";
      $data['data_list'] = $this->vendor->search_report_data();
      $this->load->view('vendor/vendor_report_html',$data); 
    }

}
