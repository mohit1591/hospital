<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Insurance_company extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('insurance_company/insurance_company_model','insurance_company');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('14','78');
        $data['page_title'] = 'Insurance Company List'; 
        $this->load->view('insurance_company/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission('14','78');
         $users_data = $this->session->userdata('auth_users');
         
        $sub_branch_details = $this->session->userdata('sub_branches_data');
       $parent_branch_details = $this->session->userdata('parent_branches_data');
      
            $list = $this->insurance_company->get_datatables();
        
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $insurance_company) {
         // print_r($insurance_company);die;
            $no++;
            $row = array();
            if($insurance_company->status==1)
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
            if($users_data['parent_id']==$insurance_company->branch_id){
               $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$insurance_company->id.'">'.$check_script;
             }
             else{
               $row[]='';
             } 
            $row[] = $insurance_company->insurance_company;  
            $row[] = $status;
            $row[] = date('d-M-Y H:i A',strtotime($insurance_company->created_date)); 
 
          
          $btnedit='';
          $btndelete='';
        
          if($users_data['parent_id']==$insurance_company->branch_id){
               if(in_array('80',$users_data['permission']['action'])){
                    $btnedit = ' <a onClick="return edit_insurance_company('.$insurance_company->id.');" class="btn-custom" href="javascript:void(0)" style="'.$insurance_company->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
               }
               if(in_array('81',$users_data['permission']['action'])){
                    $btndelete = ' <a class="btn-custom" onClick="return delete_insurance_company('.$insurance_company->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';
               }
          }
         
             $row[] = $btnedit.$btndelete;  
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->insurance_company->count_all(),
                        "recordsFiltered" => $this->insurance_company->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
        unauthorise_permission('14','79');
        $data['page_title'] = "Add Insurance Company";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'insurance_company'=>"",
                                  'panel_code'=>"",
                                  'status'=>"1"
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate('');
            if($this->form_validation->run() == TRUE)
            {
                $this->insurance_company->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('insurance_company/add',$data);       
    }
    
    public function edit($id="")
    {
      unauthorise_permission('14','80');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->insurance_company->get_by_id($id);  
        $data['page_title'] = "Update Insurance Company";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'insurance_company'=>$result['insurance_company'], 
                                  'panel_code'=>$result['panel_code'], 
                                  'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate($id);
            if($this->form_validation->run() == TRUE)
            {
                $this->insurance_company->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('insurance_company/add',$data);       
      }
    }
     
    private function _validate($id='')
    {
        
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('insurance_company', 'insurance company', 'trim|required|callback_check_unique_value['.$id.']'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'insurance_company'=>$post['insurance_company'], 
                                        'status'=>$post['status']
                                       ); 
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {
       unauthorise_permission('14','81');
       if(!empty($id) && $id>0)
       {
           $result = $this->insurance_company->delete($id);
           $response = "Insurance Company successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission('14','81');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->insurance_company->deleteall($post['row_id']);
            $response = "Insurance Company successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    { 
      
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->insurance_company->get_by_id($id);  
        $data['page_title'] = $data['form_data']['insurance_company']." detail";
        $this->load->view('insurance_company/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission('14','82');
        $data['page_title'] = 'Insurance Company Archive List';
        $this->load->helper('url');
        $this->load->view('insurance_company/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('14','82');
        $this->load->model('insurance_company/insurance_company_archive_model','insurance_company_archive'); 

        

               $list = $this->insurance_company_archive->get_datatables();
              
                    
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $insurance_company) {
         // print_r($insurance_company);die;
            $no++;
            $row = array();
            if($insurance_company->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$insurance_company->id.'">'.$check_script; 
            $row[] = $insurance_company->insurance_company;  
            $row[] = $status;
            $row[] = date('d-M-Y H:i A',strtotime($insurance_company->created_date)); 
            $users_data = $this->session->userdata('auth_users');
          $btnrestore='';
          $btndelete='';
          if(in_array('84',$users_data['permission']['action'])){
              $btnrestore = ' <a onClick="return restore_insurance_company('.$insurance_company->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
          }
          if(in_array('83',$users_data['permission']['action'])){
               $btndelete = ' <a onClick="return trash('.$insurance_company->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
          }
          $row[]= $btnrestore.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->insurance_company_archive->count_all(),
                        "recordsFiltered" => $this->insurance_company_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission('14','84');
        $this->load->model('insurance_company/insurance_company_archive_model','insurance_company_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->insurance_company_archive->restore($id);
           $response = "Insurance Company successfully restore in Insurance Company list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission('14','84');
        $this->load->model('insurance_company/insurance_company_archive_model','insurance_company_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->insurance_company_archive->restoreall($post['row_id']);
            $response = "Insurance Company successfully restore in Insurance Company list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('14','83');
        $this->load->model('insurance_company/insurance_company_archive_model','insurance_company_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->insurance_company_archive->trash($id);
           $response = "Insurance Company successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('14','83');
        $this->load->model('insurance_company/insurance_company_archive_model','insurance_company_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->insurance_company_archive->trashall($post['row_id']);
            $response = "Insurance Company successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function insurance_company_dropdown()
  {

      $insurance_company_list = $this->insurance_company->insurance_company_list();
      $dropdown = '<option value="">Select insurance company</option>'; 
      if(!empty($insurance_company_list))
      {
        foreach($insurance_company_list as $insurance_company)
        {
           $dropdown .= '<option value="'.$insurance_company->id.'">'.$insurance_company->insurance_company.'</option>';
        }
      } 
      echo $dropdown; 
  }
  
  // this function is used for sample excel sheet doctor list on 23-04-2018
      public function sample_import_insurance_company_excel()
    {
            //unauthorise_permission(97,627);
            // Starting the PHPExcel library
        
            $this->load->library('excel');
            $this->excel->IO_factory();
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
            $objPHPExcel->setActiveSheetIndex(0);
            // Field names in the first row
            $fields = array('Insurance Company(*)','Code*');

            
      
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
            header("Content-Disposition: attachment; filename=insurance_company_import_sample_".time().".xls");  
            header("Pragma: no-cache"); 
            header("Expires: 0");
            ob_end_clean();
            $objWriter->save('php://output');
           
    }


    // this function is used for import excel sheet doctor list on 23-04-2018
     public function import_insurance_company_excel()
    {
        //unauthorise_permission(97,628);
        $this->load->library('excel');
        $data['page_title'] = 'Import Insurance Company Excel';
        $arr_data = array();
        $header = array();
        $path='';
       // print_r($_FILES);die();
      //echo"hello";print_r($_FILES); 
        if(isset($_FILES) && !empty($_FILES))
        {
          
            $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
            if(!isset($_FILES['insurance_list']) || $_FILES['insurance_list']['error']>0)
            {
               
               $this->form_validation->set_rules('insurance_list', 'file', 'trim|required');  
            } 
           // $this->form_validation->set_rules('name', 'name', 'trim|required');  
            if($this->form_validation->run() == TRUE)
            {
               
                $config['upload_path']   = DIR_UPLOAD_PATH.'temp/'; 
                $config['allowed_types'] = '*'; 
                $this->load->library('upload', $config);
                if(!$this->upload->do_upload('insurance_list')) 
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
                    $total_company = count($arrs_data);
                    
                   $array_keys = array('insurance_company','panel_code');

                    $count_array_keys = count($array_keys);
                    $array_values_keys = array('A','B');
                    $company_data = array();
                    
                    $j=0;
                    $m=0;
                    $data='';
                    $company_all_data= array();
                    for($i=0;$i<$total_company;$i++)
                    {
                        $med_data_count_values[$i] = count($arrs_data[$i]);
                        for($p=0;$p<$count_array_keys;$p++)
                        {
                            if(array_key_exists($array_values_keys[$p],$arrs_data[$i]))
                            {
                                $company_all_data[$i][$array_keys[$p]] = $arrs_data[$i][$array_values_keys[$p]];
                            }
                            else
                            {
                                $company_all_data[$i][$array_keys[$p]] ="";
                            }
                        }
                    }

                   // echo '<pre>'; print_r($doctor_all_data); exit;
                    $this->insurance_company->save_all_insurance($company_all_data);
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
            echo 1;
        }
        else{
           $this->load->view('insurance_company/import_insurance_excel',$data);
        }
       
    } 

    
   function check_unique_value($ins_comp, $id='') 
      {     
        $users_data = $this->session->userdata('auth_users');
        $result = $this->insurance_company->check_unique_value($users_data['parent_id'], $ins_comp,$id);
        if($result == 0)
            $response = true;
        else {
            $this->form_validation->set_message('check_unique_value', 'Insurance company already exist.');
            $response = false;
        }
        return $response;
      }

}
?>