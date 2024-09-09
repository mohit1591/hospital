<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ot_management extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('ot_management/ot_management_model','otmanagement');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('192','1133');
        $data['page_title'] = 'Operation Management List'; 
        $this->load->view('ot_management/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission('192','1133');
        $users_data = $this->session->userdata('auth_users');
        $list = $this->otmanagement->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $ot) {
         // print_r($relation);die;
            $no++;
            $row = array();
            if($ot->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$ot->id.'">'.$check_script; 
            $row[] = $ot->name;
            $row[] = $ot->operation_type;  
            $row[] = $ot->amount;
            $row[] = $ot->hours;
            $row[] = $status;
           // $row[] = date('d-M-Y H:i A',strtotime($ot->created_date)); 
           
          $btnedit='';
          $btndelete='';
          if(in_array('1131',$users_data['permission']['action'])){
               $btnedit = ' <a onClick="return edit_ot_management('.$ot->id.');" class="btn-custom" href="javascript:void(0)" style="'.$ot->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
           }
          if(in_array('1130',$users_data['permission']['action'])){
               $btndelete = ' <a class="btn-custom" onClick="return delete_ot_management('.$ot->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
         }
          $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->otmanagement->count_all(),
                        "recordsFiltered" => $this->otmanagement->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    

    
    public function add()
    {
        unauthorise_permission('192','1132');
        $data['page_title'] = "Add Operation";  
        $data['ot_type_list']=$this->otmanagement->type_list();
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'name'=>"",
                                 // 'type'=>"",
                                 // 'ot_type_id'=>"",
                                  'ot_pacakge_type_id'=>"",
                                  'amount'=>"",
                                  'hours'=>"",
                                  'status'=>"1",
                                  'ot_details' => array(),
                                  'ot_procedure'=>'',
                                  );    
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->otmanagement->save();
                echo 1;
                return false;
            }
            else
            {
              $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('ot_management/add',$data);       
    }
    
    public function edit($id="")
    {
      unauthorise_permission('192','1131');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->otmanagement->get_by_id($id);  
        $data['ot_type_list']=$this->otmanagement->type_list();
        $result_ot_details = $this->otmanagement->get_by_id_ot_details($id);
        $data['page_title'] = "Update Operation";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'name'=>$result['name'], 
                                  'ot_pacakge_type_id'=>$result['type'],
                                   'ot_details'=>$result_ot_details,
                                 // 'type'=>$result['operation_type'],
                                 // 'ot_type_id'=>$result['ot_type_id'],
                                  'hours'=>$result['hours'],
                                  'amount'=>$result['amount'],
                                  'status'=>$result['status'],
                                  'ot_procedure'=>$result['ot_procedure'],
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->otmanagement->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('ot_management/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('name', 'name', 'trim|required|callback_check_operation_name'); 
        $this->form_validation->set_rules('ot_pacakge_type_id', 'type', 'trim|required'); 
        $this->form_validation->set_rules('amount', 'amount', 'trim|required'); 
        $this->form_validation->set_rules('hours', 'hours', 'trim|required'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
              $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'name'=>$post['name'],
                                       // 'type'=>$post['type'],
                                        'ot_pacakge_type_id'=>$post['ot_pacakge_type_id'],
                                        //'ot_type_id'=>$post['ot_type_id'],
                                        'amount'=>$post['amount'],
                                        'hours'=>$post['hours'],
                                        'status'=>$post['status'],
                                        'ot_procedure'=>$post['ot_procedure']
                                       ); 
            return $data['form_data'];
        }   
    }

     public function check_operation_name($str)
    {

      $post = $this->input->post();

      if(!empty($str))
      {
          $this->load->model('general/general_model','general'); 
          if(!empty($post['data_id']) && $post['data_id']>0)
          {
                 $data_cat= $this->otmanagement->get_by_id($post['data_id']);
                if($data_cat['name']==$str && $post['data_id']==$data_cat['id'])
                {
                  return true;  
                }
                else
                {
                  $operationdata = $this->general->check_operation_name($str);

                  if(empty($operationdata))
                  {
                  return true;
                  }
                  else
                  {
                  $this->form_validation->set_message('check_operation_name', 'The operation name already exists.');
                  return false;
                  }
                }
          }
          else
          {
                  $operationdata = $this->general->check_operation_name($str);
                  if(empty($operationdata))
                  {
                     return true;
                  }
                  else
                  {
                  $this->form_validation->set_message('check_operation_name', 'The operation name already exists.');
                  return false;
                  }
          }  
      }
      else
      {
        $this->form_validation->set_message('check_operation_name', 'The operation name field is required.');
              return false; 
      } 
    }
 
    public function delete($id="")
    {
       unauthorise_permission('192','1130');
       if(!empty($id) && $id>0)
       {
           $result = $this->otmanagement->delete($id);
           $response = "Operation management successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
       unauthorise_permission('192','1130');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->otmanagement->deleteall($post['row_id']);
            $response = "Operation management successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->otmanagement->get_by_id($id);  
        $data['page_title'] = $data['form_data']['name']." detail";
        $this->load->view('ot_management/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
       unauthorise_permission('192','1129');
        $data['page_title'] = 'Operation Management Archive List';
        $this->load->helper('url');
        $this->load->view('ot_management/archive',$data);
    }

    public function archive_ajax_list()
    {
         unauthorise_permission('192','1129');
        $this->load->model('ot_management/ot_management_archive_model','ot_management_archive'); 

        $list = $this->ot_management_archive->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $ot_management) { 
            $no++;
            $row = array();
            if($ot_management->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$ot_management->id.'">'.$check_script; 
            $row[] = $ot_management->name;
            $row[] = $ot_management->operation_type;  
            $row[] = $ot_management->amount;
            $row[] = $ot_management->hours;
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($ot_management->created_date)); 
            $users_data = $this->session->userdata('auth_users');
            $btnrestore='';
            $btndelete='';
          if(in_array('1127',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_ot_management('.$ot_management->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
           }
          if(in_array('1128',$users_data['permission']['action'])){
               $btndelete = ' <a onClick="return trash('.$ot_management->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
          }
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->ot_management_archive->count_all(),
                        "recordsFiltered" => $this->ot_management_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission('192','1127');
          $this->load->model('ot_management/ot_management_archive_model','ot_management_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->ot_management_archive->restore($id);
           $response = "Operation management successfully restore in Operation management list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission('192','1127');
        $this->load->model('ot_management/ot_management_archive_model','ot_management_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->ot_management_archive->restoreall($post['row_id']);
            $response = "Operation management successfully restore in Operation management list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('192','1128');
         $this->load->model('ot_management/ot_management_archive_model','ot_management_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->ot_management_archive->trash($id);
           $response = "Operation management successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('192','1128');
        $this->load->model('ot_management/ot_management_archive_model','ot_management_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->ot_management_archive->trashall($post['row_id']);
            $response = "Operation management successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function ot_summary_dropdown()
  {
	  $ot_pacakge_list = $this->otmanagement->ot_management_list();
      $dropdown = '<option value="">Select Operation Management</option>'; 
      if(!empty($ot_pacakge_list))
      {
        foreach($ot_pacakge_list as $otmanagement)
        {
           $dropdown .= '<option value="'.$otmanagement->id.'">'.$otmanagement->name.'</option>';
        }
      } 
      echo $dropdown; 
  }

  function get_item_values($vals="")
    {

        if(!empty($vals))
        {
            $result = $this->otmanagement->get_vals($vals);  
            if(!empty($result))
            {
              echo json_encode($result,true);
            } 
        } 
    }


       // this function is used for sample excel sheet ot management list on 23-04-2018
      public function sample_import_ot_management_excel()
    {
            //unauthorise_permission(97,627);
            // Starting the PHPExcel library
        
            $this->load->library('excel');
            $this->excel->IO_factory();
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
            $objPHPExcel->setActiveSheetIndex(0);
            // Field names in the first row
            $fields = array('Operation Name*','Operation Type*','Operation Hours*','Amount *');

            
      
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
            header("Content-Disposition: attachment; filename=ot_management_import_sample_".time().".xls");  
            header("Pragma: no-cache"); 
            header("Expires: 0");
            ob_end_clean();
            $objWriter->save('php://output');
           
    }


    // this function is used for import excel sheet management list on 23-04-2018
     public function import_ot_management_excel()
    {
        //unauthorise_permission(97,628);
        $this->load->library('excel');
        $data['page_title'] = 'Import OT Management Excel';
        $arr_data = array();
        $header = array();
        $path='';

      //echo"hello";print_r($_FILES); 
        if(isset($_FILES) && !empty($_FILES))
        {
          
            $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
            if(!isset($_FILES['manage_list']) || $_FILES['manage_list']['error']>0)
            {
               
               $this->form_validation->set_rules('manage_list', 'file', 'trim|required');  
            } 
            $this->form_validation->set_rules('name', 'name', 'trim|required');  
            if($this->form_validation->run() == TRUE)
            {
               
                $config['upload_path']   = DIR_UPLOAD_PATH.'temp/'; 
                $config['allowed_types'] = '*'; 
                $this->load->library('upload', $config);
                if(!$this->upload->do_upload('manage_list')) 
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
                    $total_ot = count($arrs_data);
                    
                   $array_keys = array('name','type','hours','amount');

                    $count_array_keys = count($array_keys);
                    $array_values_keys = array('A','B','C','D');
                    $ot_data = array();
                    
                    $j=0;
                    $m=0;
                    $data='';
                    $ot_all_data= array();
                    for($i=0;$i<$total_ot;$i++)
                    {
                        $med_data_count_values[$i] = count($arrs_data[$i]);
                        for($p=0;$p<$count_array_keys;$p++)
                        {
                            if(array_key_exists($array_values_keys[$p],$arrs_data[$i]))
                            {
                                $ot_all_data[$i][$array_keys[$p]] = $arrs_data[$i][$array_values_keys[$p]];
                            }
                            else
                            {
                                $ot_all_data[$i][$array_keys[$p]] ="";
                            }
                        }
                    }

                   // echo '<pre>'; print_r($doctor_all_data); exit;
                    $this->otmanagement->save_all_ot_management($ot_all_data);
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

        $this->load->view('ot_management/import_ot_management_excel',$data);
    } 




}
?>