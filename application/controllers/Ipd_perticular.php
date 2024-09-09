<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ipd_perticular extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('ipd_perticular/ipd_perticular_model','ipd_perticular');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        //unauthorise_permission('109','677');
        $data['page_title'] = 'Particular List'; 
        $this->load->view('ipd_perticular/list',$data);
    }

    public function ajax_list()
    { 
      //unauthorise_permission('109','677');
      $users_data = $this->session->userdata('auth_users');
      $sub_branch_details = $this->session->userdata('sub_branches_data');
      $parent_branch_details = $this->session->userdata('parent_branches_data');
      $list = $this->ipd_perticular->get_datatables();
        
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $ipd_perticular) {
         // print_r($ipd_perticular);die;
            $no++;
            $row = array();
            if($ipd_perticular->status==1)
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
            if($users_data['parent_id']==$ipd_perticular->branch_id)
            {
               $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$ipd_perticular->id.'">'.$check_script;
            }else{
               $row[]='';
            }
            
            $row[] = $ipd_perticular->particular;
            $row[] = $ipd_perticular->charge;
           
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($ipd_perticular->created_date)); 
            $btnedit='';
            $btndelete='';
          
            if($users_data['parent_id']==$ipd_perticular->branch_id)
            {
              //if(in_array('679',$users_data['permission']['action'])){
               $btnedit =' <a onClick="return edit_ipd_perticular('.$ipd_perticular->id.');" class="btn-custom" href="javascript:void(0)" style="'.$ipd_perticular->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
              //}
             //if(in_array('680',$users_data['permission']['action'])){
                    $btndelete = ' <a class="btn-custom" onClick="return delete_ipd_perticular('.$ipd_perticular->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';   
               // }
            }
          
             $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->ipd_perticular->count_all(),
                        "recordsFiltered" => $this->ipd_perticular->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

     public function particular_excel()
    {
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          // Field names in the first row
          $fields = array('ID','Code','Particular Name','Charges');
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
                //$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
                $col++;
          }
          $list = $this->ipd_perticular->search_report_data();

          $rowData = array();
          $data= array();
          if(!empty($list))
          {
               
               $m=1;
               foreach($list as $reports)
               {
                    
                    array_push($rowData,$m,$reports->particular_code,$reports->particular,$reports->charge);
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
          header("Content-Disposition: attachment; filename=partuclar_report_".time().".xls");
          header("Pragma: no-cache"); 
          header("Expires: 0");
         if(!empty($data))
         {
          ob_end_clean();
          $objWriter->save('php://output');
         }
    }
    public function particular_csv()
    {
          // Starting the PHPExcel library
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          // Field names in the first row
          $fields = array('ID','Code','Particular Name','Charges');
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
               // $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
               $col++;
          }
          $list = $this->ipd_perticular->search_report_data();
          $rowData = array();
          $data= array();
          if(!empty($list))
          {
              
               $i=0;
               $m=1;
               foreach($list as $reports)
               {
                    
                     array_push($rowData,$m,$reports->particular_code,$reports->particular,$reports->charge);
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
          header("Content-Disposition: attachment; filename=medicine_report_".time().".csv");
          header("Pragma: no-cache"); 
          header("Expires: 0");
         if(!empty($data))
         {
          ob_end_clean();
          $objWriter->save('php://output');
         }
    
    }

    public function pdf_particular()
    {    
        $data['print_status']="";
        $data['data_list'] = $this->ipd_perticular->search_report_data();
        //echo "<pre>";print_r($data['data_list']);die;
        $this->load->view('ipd_perticular/perticular_report_html',$data);
        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        //echo $html; exit;
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("ipd_particular_".time().".pdf");
    }
    public function print_particular()
    {    
      $data['print_status']="1";
      $data['data_list'] = $this->ipd_perticular->search_report_data();
      $this->load->view('ipd_perticular/perticular_report_html',$data); 
    }
    
    
    public function add()
    {
       // unauthorise_permission('109','678');
        $data['page_title'] = "Add Particular";  
        $post = $this->input->post();

        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'perticular'=>"",
                                  'particular_code'=>'',
                                  'charge'=>"",
                                  //'panel_charge'=>"",
                                  "particular_code"=>"",
                                  'status'=>"1",
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->ipd_perticular->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('ipd_perticular/add',$data);       
    }
     // -> function to find gender according to selected ipd_perticular
    // -> change made by: mahesh:date:23-05-17
    // -> starts:
    public function find_gender(){
         $this->load->model('general/general_model'); 
         $ipd_perticular_id = $this->input->post('ipd_perticular_id');
         $data='';
          if(!empty($ipd_perticular_id)){
               $result = $this->general_model->find_gender($ipd_perticular_id);
               if(!empty($result))
               {
                  $male = "";
                  $female = ""; 
                  $others=""; 
                  if($result['gender']==1)
                  {
                     $male = 'checked="checked"';
                  } 
                  else if($result['gender']==0)
                  {
                     $female = 'checked="checked"';
                  } 
                  else if($result['gender']==2){
                    $others = 'checked="checked"';
                  }
                  $data.='<input type="radio" name="gender" '.$male.' value="1" /> Male &nbsp;
                          <input type="radio" name="gender" '.$female.' value="0" /> Female &nbsp;
                          <input type="radio" name="gender" '.$others.' value="2" /> Others ';
               }
 
          }
          echo $data;
    }
    // -> end:
    public function edit($id="")
    {
    // unauthorise_permission('109','679');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->ipd_perticular->get_by_id($id);  
        $data['page_title'] = "Update Particular";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'perticular'=>$result['particular'],
                                  'charge'=>$result['charge'],
                                  'particular_code'=>$result['particular_code'],
                                 // 'group_code'=>$result['group_code'],
                                  //'panel_charge'=>$result['panel_charge'], 
                                  'status'=>$result['status']
                                  
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate($id);
            if($this->form_validation->run() == TRUE)
            {
                $this->ipd_perticular->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('ipd_perticular/add',$data);       
      }
    }
     
    private function _validate($id='')
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('perticular', 'particular', 'trim|required|callback_check_unique_value['.$id.']');
        $this->form_validation->set_rules('charge', 'charge', 'trim|required');
        //$this->form_validation->set_rules('panel_charge', 'panel charge', 'trim|required');
         // $this->form_validation->set_rules('particular_code', 'code', 'trim|required'); 

        if ($this->form_validation->run() == FALSE) 
        {  
          if(!empty($post['hide_grp_id']))
    {
      $grp_code=$post['hide_grp_id'];
    }
    else{
      $grp_code=$post['group_code'];
    }
    //print_r($grp_code);die;
    $particular_code=$grp_code.$post['particular_code'];
           
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'perticular'=>$post['perticular'],
                                        'charge'=>$post['charge'],
                                        //'panel_charge'=>$post['panel_charge'], 
                                        //"particular_code"=>$particular_code,
                                        'status'=>$post['status'],
                                        
                                       ); 
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {
      // unauthorise_permission('109','680');
       if(!empty($id) && $id>0)
       {
           
           $result = $this->ipd_perticular->delete($id);
           $response = "particular successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
       // unauthorise_permission('109','680');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->ipd_perticular->deleteall($post['row_id']);
            $response = "Particulars successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->ipd_perticular->get_by_id($id);  
        $data['page_title'] = $data['form_data']['ipd_perticular']." detail";
        $this->load->view('ipd_perticular/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
       // unauthorise_permission('109','681');
        $data['page_title'] = 'Particular Archive List';
        $this->load->helper('url');
        $this->load->view('ipd_perticular/archive',$data);
    }

    public function archive_ajax_list()
    {
        //unauthorise_permission('109','681');
        $this->load->model('ipd_perticular/ipd_perticular_archive_model','ipd_perticular_archive'); 

      
               $list = $this->ipd_perticular_archive->get_datatables();
              
                
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $ipd_perticular) {
         // print_r($ipd_perticular);die;
            $no++;
            $row = array();
            if($ipd_perticular->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$ipd_perticular->id.'">'.$check_script; 
            $row[] = $ipd_perticular->particular_code;
            $row[] = $ipd_perticular->particular;
            $row[] = $ipd_perticular->charge;
            //$row[] = $ipd_perticular->panel_charge; 
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($ipd_perticular->created_date)); 
            $users_data = $this->session->userdata('auth_users');
            $btnrestore='';
            $btndelete='';
             //if(in_array('683',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_ipd_perticular('.$ipd_perticular->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
               //}
              // if(in_array('682',$users_data['permission']['action'])){
                    $btndelete = ' <a onClick="return trash('.$ipd_perticular->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
                //}
               $row[] = $btnrestore.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->ipd_perticular_archive->count_all(),
                        "recordsFiltered" => $this->ipd_perticular_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        //unauthorise_permission('109','683');
        $this->load->model('ipd_perticular/ipd_perticular_archive_model','ipd_perticular_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->ipd_perticular_archive->restore($id);
           $response = "Particular successfully restore in Perticular List.";
           echo $response;
       }
    }

    function restoreall()
    { 
        //unauthorise_permission('109','683');
        $this->load->model('ipd_perticular/ipd_perticular_archive_model','ipd_perticular_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->ipd_perticular_archive->restoreall($post['row_id']);
            $response = "Particulars successfully restore in Perticular List.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        //unauthorise_permission('109','682');
        $this->load->model('ipd_perticular/ipd_perticular_archive_model','ipd_perticular_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->ipd_perticular_archive->trash($id);
           $response = "Particular successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        //unauthorise_permission('109','682');
        $this->load->model('ipd_perticular/ipd_perticular_archive_model','ipd_perticular_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->ipd_perticular_archive->trashall($post['row_id']);
            $response = "Particular successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function ipd_perticular_dropdown()
  {
     $ipd_perticular_list = $this->ipd_perticular->get_particulars();
     $dropdown = '<option value="">Select Particular</option>'; 
  
     
     if(!empty($ipd_perticular_list))
     {
          foreach($ipd_perticular_list as $ipd_perticular)
          {
             
               $dropdown .= '<option value="'.$ipd_perticular->id.'" >'.$ipd_perticular->particular.'</option>';
          }
     } 
     echo $dropdown; 
  }
  public function get_particulars()
  {
     $post = $this->input->post();

     $ipd_particulars = $this->ipd_perticular->get_particulars();
     if(!isset($post) && empty($post['particular_id']))
     {
          $dropdown = '<option value="">Select Particular</option>'; 
       
          
          if(!empty($ipd_particulars))
          {
               foreach($ipd_particulars as $particulars)
               {
                  
                    $dropdown .= '<option value="'.$particulars->id.'" >'.$particulars->perticular.'</option>';
               }
          } 
          echo $dropdown;
     }
     else
     {
          $particular_amount = json_encode($ipd_particulars);
          echo $particular_amount;
     }

  }
  
  // this function is used for sample excel sheet doctor list on 23-04-2018
      public function sample_import_ipdperticular_excel()
    {
            //unauthorise_permission(97,627);
            // Starting the PHPExcel library
        
            $this->load->library('excel');
            $this->excel->IO_factory();
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
            $objPHPExcel->setActiveSheetIndex(0);
            // Field names in the first row
            $fields = array('Particular*','Charge*','Code','Panel Charge*');

            
      
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
            header("Content-Disposition: attachment; filename=ipdperticular_import_sample_".time().".xls");  
            header("Pragma: no-cache"); 
            header("Expires: 0");
            ob_end_clean();
            $objWriter->save('php://output');
           
    }


      // this function is used for import excel sheet doctor list on 23-04-2018
     public function import_ipdperticular_excel()
    {
        //unauthorise_permission(97,628);
        $this->load->library('excel');
        $data['page_title'] = 'Import Perticular excel';
        $arr_data = array();
        $header = array();
        $path='';

      //echo"hello";print_r($_FILES); 
        if(isset($_FILES) && !empty($_FILES))
        {
          
            $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
            if(!isset($_FILES['ipd_list']) || $_FILES['ipd_list']['error']>0)
            {
               
               $this->form_validation->set_rules('ipd_list', 'file', 'trim|required');  
            } 
            $this->form_validation->set_rules('name', 'name', 'trim|required');  
            if($this->form_validation->run() == TRUE)
            {
               
                $config['upload_path']   = DIR_UPLOAD_PATH.'patients/'; 
                $config['allowed_types'] = '*'; 
                $this->load->library('upload', $config);
                if(!$this->upload->do_upload('ipd_list')) 
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
                    $total_ipd = count($arrs_data);
                    
                   $array_keys = array('particular','charge','particular_code');

                    $count_array_keys = count($array_keys);
                    $array_values_keys = array('A','B','C');
                    $ipd_data = array();
                    
                    $j=0;
                    $m=0;
                    $data='';
                    $ipd_all_data= array();
                    for($i=0;$i<$total_ipd;$i++)
                    {
                        $med_data_count_values[$i] = count($arrs_data[$i]);
                        for($p=0;$p<$count_array_keys;$p++)
                        {
                            if(array_key_exists($array_values_keys[$p],$arrs_data[$i]))
                            {
                                $ipd_all_data[$i][$array_keys[$p]] = $arrs_data[$i][$array_values_keys[$p]];
                            }
                            else
                            {
                                $ipd_all_data[$i][$array_keys[$p]] ="";
                            }
                        }
                    }

                    $this->ipd_perticular->save_all_ipdperticular($ipd_all_data);
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

        $this->load->view('ipd_perticular/import_ipd_perticular_excel',$data);
    }
  
// op 19/08/19
  function check_unique_value($particular, $id='') 
  {     
        $users_data = $this->session->userdata('auth_users');
        $result = $this->ipd_perticular->check_unique_value($users_data['parent_id'], $particular, $id);
        if($result == 0)
            $response = true;
        else {
            $this->form_validation->set_message('check_unique_value', 'Particular already exist.');
            $response = false;
        }
        return $response;
    }

    public function sample_import_particular_excel()
    {
            //unauthorise_permission(97,627);
            // Starting the PHPExcel library
        
            $this->load->library('excel');
            $this->excel->IO_factory();
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
            $objPHPExcel->setActiveSheetIndex(0);
            // Field names in the first row
            $fields = array('Particular(*)','Charge');

            
      
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
            header("Content-Disposition: attachment; filename=particular_import_sample_".time().".xls");  
            header("Pragma: no-cache"); 
            header("Expires: 0");
            ob_end_clean();
            $objWriter->save('php://output');
           
    }

     // this function is used for import excel sheet doctor list on 23-04-2018
    

}
?>