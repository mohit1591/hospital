<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Documents extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('ambulance/documents_model','documents');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('405','2456');
        $data['page_title'] = 'Document List'; 
        $this->load->view('ambulance/documents/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission('405','2456');
        $list = $this->documents->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $document) {
         // print_r($relation);die;
            $no++;
            $row = array();
            if($document->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$document->id.'">'.$check_script; 
            $row[] = $document->document;
            $row[] = $status;
            //$row[] = date('d-M-Y h:i A',strtotime($document->created_date)); 
            $users_data = $this->session->userdata('auth_users');
          $btnedit='';
          $btndelete='';
          if(in_array('2455',$users_data['permission']['action'])){
               $btnedit = ' <a onClick="return edit_document('.$document->id.');" class="btn-custom" href="javascript:void(0)" style="'.$document->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
          }
           if(in_array('2459',$users_data['permission']['action'])){
               $btndelete = ' <a class="btn-custom" onClick="return delete_document('.$document->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
          }
          $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->documents->count_all(),
                        "recordsFiltered" => $this->documents->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
        unauthorise_permission('405','2455');
        $data['page_title'] = "Add document";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'document'=>"",
                                  'status'=>"1"
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate('');
            if($this->form_validation->run() == TRUE)
            {
                $this->documents->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('ambulance/documents/add',$data);       
    }
    
    public function edit($id="")
    {
      unauthorise_permission('405','2454');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->documents->get_by_id($id);  
        $data['page_title'] = "Update document";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'document'=>$result['document'], 
                                  'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate($id);
            if($this->form_validation->run() == TRUE)
            {
                $this->documents->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('ambulance/documents/add',$data);       
      }
    }
     
    private function _validate($id='')
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('document', 'document', 'trim|required|callback_check_unique_value['.$id.']'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'document'=>$post['document'],
                                        'status'=>$post['status']
                                       ); 
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {
       unauthorise_permission('405','2459');
       if(!empty($id) && $id>0)
       {
           $result = $this->documents->delete($id);
           $response = "Document successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
       unauthorise_permission('405','2459');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->documents->deleteall($post['row_id']);
            $response = "Document successfully deleted.";
            echo $response;
        }
    }


  public function document_dropdown()
  {

      $document_list = $this->documents->document_list();
      $dropdown = '<option value="">Select document</option>'; 
      if(!empty($document_list))
      {
        foreach($document_list as $document)
        {
           $dropdown .= '<option value="'.$document->id.'">'.$document->document.'</option>';
        }
      } 
      echo $dropdown; 
  }
  
  // this function is used for sample excel sheet doctor list on 23-04-2018
      public function sample_import_document_excel()
    {
            //unauthorise_permission(97,627);
            // Starting the PHPExcel library
        
            $this->load->library('excel');
            $this->excel->IO_factory();
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
            $objPHPExcel->setActiveSheetIndex(0);
            // Field names in the first row
            $fields = array('document(*)');

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
            header('Content-Type: application/vnd.ms-excel charset=UTF-8');
            header("Content-Disposition: attachment; filename=document_import_sample_".time().".xls");  
            header("Pragma: no-cache"); 
            header("Expires: 0");
            ob_end_clean();
            $objWriter->save('php://output');
           
    }

     // this function is used for import excel sheet doctor list on 23-04-2018
    public function import_document_excel()
    {
        $this->load->library('excel');
        $data['page_title'] = 'Import Document excel';
        $arr_data = array();
        $header = array();
        $path='';
        if(isset($_FILES) && !empty($_FILES))
        {
          
            $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
            if(!isset($_FILES['document_list']) || $_FILES['document_list']['error']>0)
            {
               
               $this->form_validation->set_rules('document_list', 'file', 'trim|required');  
            } 
            $this->form_validation->set_rules('name', 'name', 'trim|required');  
            if($this->form_validation->run() == TRUE)
            {
               
                $config['upload_path']   = DIR_UPLOAD_PATH.'patients/'; 
                $config['allowed_types'] = '*'; 
                $this->load->library('upload', $config);
                if(!$this->upload->do_upload('document_list')) 
                {
                    $error = array('error' => $this->upload->display_errors()); 
                    $data['file_upload_eror'] = $error;
                }
                else 
                { 
                    $data = $this->upload->data();
                    $path = $config['upload_path'].$data['file_name'];
                    //read file from path
                    $objPHPExcel = PHPExcel_IOFactory::load($path);
                    $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
                    foreach ($cell_collection as $cell) 
                    {
                        $column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
                        $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
                        $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
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

                if(!empty($arr_data))
                {
                    $arrs_data = array_values($arr_data);
                    $total_paticular = count($arrs_data);
                    
                   $array_keys = array('document','charge');

                    $count_array_keys = count($array_keys);
                    $array_values_keys = array('A','B');
                    $paticular_data = array();
                    
                    $j=0;
                    $m=0;
                    $data='';
                    $document_all_data= array();
                    for($i=0;$i<$total_paticular;$i++)
                    {
                        $med_data_count_values[$i] = count($arrs_data[$i]);
                        for($p=0;$p<$count_array_keys;$p++)
                        {
                            if(array_key_exists($array_values_keys[$p],$arrs_data[$i]))
                            {
                                $document_all_data[$i][$array_keys[$p]] = $arrs_data[$i][$array_values_keys[$p]];
                            }
                            else
                            {
                                $document_all_data[$i][$array_keys[$p]] ="";
                            }
                        }
                    }
                    $this->documents->save_all_document($document_all_data);
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

        $this->load->view('ambulance/documents/import_document_excel',$data);
    } 

  // op 19/08/19
  function check_unique_value($document, $id='') 
  {     
        $users_data = $this->session->userdata('auth_users');
        $result = $this->documents->check_unique_value($users_data['parent_id'], $document,$id);
        if($result == 0)
            $response = true;
        else {
            $this->form_validation->set_message('check_unique_value', 'Document already exist.');
            $response = false;
        }
        return $response;
    }

}
?>