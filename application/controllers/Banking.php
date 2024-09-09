<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Banking extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('banking/banking_model','banking','banking_archive_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        unauthorise_permission(95,612);  
        $data['page_title'] = 'Banking List'; 
        $data['form_data'] = array(
                                    "start_date"=>'',
                                    "amount"=>'',
                                    "end_date"=>"",
                                   );

        $this->load->view('banking/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission(95,612);
        $users_data = $this->session->userdata('auth_users');
      
        $sub_branch_details = $this->session->userdata('sub_branches_data');
       $parent_branch_details = $this->session->userdata('parent_branches_data');
      
            $list = $this->banking->get_datatables();
       
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        $account_type='';
        foreach ($list as $banking) {
         // print_r($banking);die;
            $no++;
            $row = array();
            if($banking->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            }
            ///// State name ////////
            $state = "";
            if(!empty($banking->state))
            {
                $state = " ( ".ucfirst(strtolower($banking->state))." )";
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
            ////////// Check list end /////////////
            if($users_data['parent_id']==$banking->branch_id){ 
                $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$banking->id.'">'.$check_script; 
            }else{
                $row[]='';
            }
             $gte_bank_name= $this->banking->get_bank_name($banking->bank_name);
             //print_r($gte_bank_name);
              $row[] = $banking->bank_name; 
            $row[] = $banking->account_holder; 
            //if(isset($gte_bank_name[0]->bank_name)){
               //$row[] = $gte_bank_name[0]->bank_name; 
            // }else{
            
              $row[]= $banking->account_no;
           //  }
           
            $row[] = $banking->amount; 
            $row[] = date('d-m-Y',strtotime($banking->deposite_date)); 
            $row[] = $status;
            $row[] = date('d-M-Y H:i A',strtotime($banking->created_date)); 
 
            //Action button /////
            $btnedit = ""; 
            $btndelete = "";
         
             if($users_data['parent_id']==$banking->branch_id){
                if(in_array('32',$users_data['permission']['action'])) 
                {
                   $btnedit = ' <a onClick="return edit_banking('.$banking->id.');" class="btn-custom" href="javascript:void(0)" style="'.$banking->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
                }
                if(in_array('33',$users_data['permission']['action'])) 
                {
                   $btndelete = ' <a class="btn-custom" onClick="return delete_banking('.$banking->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a>';
                } 
            }
            // End Action Button //

             $row[] = $btnedit.$btndelete;  
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->banking->count_all(),
                        "recordsFiltered" => $this->banking->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
        unauthorise_permission(95,613);
        $data['page_title'] = "Add Banking";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'account_id'=>"", 
                                  'deposite_date'=>date('d-m-Y'),
                                  'amount'=>'',
                                  'status'=>"1"
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->banking->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('banking/add',$data);       
    }
    
    public function edit($id="")
    {
      unauthorise_permission(95,614);
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->banking->get_by_id($id); 
        $this->load->model('general/general_model');
        $data['country_list'] = $this->general_model->country_list();
        $data['type_list'] = $this->banking->banking_list();  
        $data['page_title'] = "Update Banking";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                   'account_id'=>$result['account_id'], 
                                  'deposite_date'=>$result['deposite_date'],
                                  'amount'=>$result['amount'],
 
                                  'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->banking->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }

        //print_r($data);die;
       $this->load->view('banking/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('account_id', 'account ', 'trim|required'); 
        $this->form_validation->set_rules('deposite_date', 'deposite date', 'trim|required');
        $this->form_validation->set_rules('amount', 'amount', 'trim|required');
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                      'data_id'=>$post['data_id'],
                                      'account_id'=>$post['account_id'], 
                                      'deposite_date'=>$post['deposite_date'],
                                      'amount'=>$post['amount'],
                                      'status'=>$post['status']
                                       ); 
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {
       unauthorise_permission(95,615);
       if(!empty($id) && $id>0)
       {
           $result = $this->banking->delete($id);
           $response = "Banking successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
       unauthorise_permission(95,615);
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->banking->deleteall($post['row_id']);
            $response = "Banking successfully deleted.";
            echo $response;
        }
    }

     


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission(95,616);
        $data['page_title'] = 'Banking Archive List';
        $this->load->helper('url');
        $this->load->view('banking/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission(95,616);
        $this->load->model('banking/banking_archive_model','banking_archive'); 
        $users_data = $this->session->userdata('auth_users');
        

               $list = $this->banking_archive->get_datatables();
              
              
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $banking) {
         // print_r($banking);die;
            $no++;
            $row = array();
            if($banking->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            }
            ///// State name ////////
            $state = "";
            if(!empty($banking->state))
            {
                $state = " ( ".ucfirst(strtolower($banking->state))." )";
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
            ////////// Check list end ///////////// 
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$banking->id.'">'.$check_script; 
              $gte_bank_name= $this->banking->get_bank_name($banking->bank_name);
             //print_r($gte_bank_name);
            $row[] = $banking->bank_name; 
            $row[] = $banking->account_holder; 
            //if(isset($gte_bank_name[0]->bank_name)){
               //$row[] = $gte_bank_name[0]->bank_name; 
            // }else{
            
              $row[]= $banking->account_no;
           //  }
           
             $row[] = $banking->amount; 
           
             $row[] = date('d-m-Y',strtotime($banking->deposite_date)); 
           
            $row[] = $status;
            $row[] = date('d-M-Y H:i A',strtotime($banking->created_date)); 
            
            //Action button /////
            $btn_restore = ""; 
            $btn_delete = "";
            if(in_array('36',$users_data['permission']['action'])) 
            {
               $btn_restore = ' <a onClick="return restore_banking('.$banking->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
            } 
            if(in_array('35',$users_data['permission']['action'])) 
            {
                $btn_delete = ' <a onClick="return trash('.$banking->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>';
            } 
            // End Action Button //


      $row[] = $btn_restore.$btn_delete; 
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->banking_archive->count_all(),
                        "recordsFiltered" => $this->banking_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function banking_excel()
    {
        //unauthorise_permission(63,578);
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          // Field names in the first row
          $fields = array('Bank Name','A/c Name','A/c No.','Amount','Deposit Date');
          $col = 0;
           $objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          foreach ($fields as $field)
          {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
               $col++;
          }
          $list =$this->banking->search_report_data();
          //print_r($list);die;
          $rowData = array();
          $data= array();
          if(!empty($list))
          {
               
               $i=0;
               foreach($list as $reports)
               {

               
                    
                    array_push($rowData,$reports->bank_name,$reports->account_holder,$reports->account_no,$reports->amount,date('d-M-Y H:i A',strtotime($reports->deposite_date)));
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
          header("Content-Disposition: attachment; filename=banking_report_".time().".xls");
          header("Pragma: no-cache"); 
          header("Expires: 0");
         if(!empty($data))
         {
          ob_end_clean();
          $objWriter->save('php://output');
         }
    }

    public function banking_csv()
    {
        unauthorise_permission(63,579);
        // Starting the PHPExcel library
        $this->load->library('excel');
        $this->excel->IO_factory();
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
        $objPHPExcel->setActiveSheetIndex(0);
        // Field names in the first row
        $fields = array('Bank Name','A/c Name','A/c No.','Amount','Deposit Date');
        $col = 0;
        foreach ($fields as $field)
        {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
            $col++;
        }
        $list = $this->banking->search_report_data();
        $rowData = array();
        $data= array();
        if(!empty($list))
        {
               
           $i=0;
           foreach($list as $reports)
           {
                 array_push($rowData,$reports->bank_name,$reports->account_holder,$reports->account_no,$reports->amount,date('d-M-Y H:i A',strtotime($reports->deposite_date)));
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
        header("Content-Disposition: attachment; filename=banking_report_".time().".csv");  
        header("Pragma: no-cache"); 
        header("Expires: 0");
        if(!empty($data))
        {
            ob_end_clean();
            $objWriter->save('php://output');
        }
      
    }

    public function advance_search()
      {
          $this->load->model('general/general_model'); 
          $data['page_title'] = "Advance Search";
          $post = $this->input->post();
          $data['form_data'] = array(
                                    "start_date"=>'',
                                    "amount"=>'',
                                     "end_date"=>"",
                                   
                                   
                                    );
          if(isset($post) && !empty($post))
          {
              $marge_post = array_merge($data['form_data'],$post);
              $this->session->set_userdata('bank_search', $marge_post);
          }
          $bank_search = $this->session->userdata('bank_search');
          if(isset($bank_search) && !empty($bank_search))
          {
              $data['form_data'] = $bank_search;
          }
         // $this->load->view('medicine_stock/advance_search',$data);
   }

    public function pdf_banking()
    {    
        unauthorise_permission(63,580);
        $data['print_status']="";
        $data['data_list'] = $this->banking->search_report_data();
        $this->load->view('banking/banking_report_html',$data);
        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        //echo $html; exit;
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("banking_report_".time().".pdf");
    }
    public function print_banking()
    {    
      //unauthorise_permission(63,581);
      $data['print_status']="1";
      $data['data_list'] = $this->banking->search_report_data();
      $this->load->view('banking/banking_report_html',$data); 
    }

    public function restore($id="")
    {
       unauthorise_permission(95,618);
        $this->load->model('banking/banking_archive_model','banking_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->banking_archive->restore($id);
           $response = "Banking successfully restore in Bank Account list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission(95,618);
        $this->load->model('banking/banking_archive_model','banking_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->banking_archive->restoreall($post['row_id']);
            $response = "Banking successfully restore in Bank Account list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
       unauthorise_permission(95,617);
        $this->load->model('banking/banking_archive_model','banking_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->banking_archive->trash($id);
           $response = "Banking successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission(95,617);
        $this->load->model('banking/banking_archive_model','banking_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->banking_archive->trashall($post['row_id']);
            $response = "Banking successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function banking_dropdown()
  {
      $banking_list = $this->banking->banking_list();
      $dropdown = '<option value="">Select Bank Account</option>'; 
      if(!empty($banking_list))
      {
        foreach($banking_list as $banking)
        {
           $dropdown .= '<option value="'.$banking->id.'">'.$banking->emp_type.'</option>';
        }
      } 
      echo $dropdown; 
  }

}
?>