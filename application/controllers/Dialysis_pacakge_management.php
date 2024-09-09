<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dialysis_pacakge_management extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('dialysis_pacakge_management/dialysis_pacakge_management_model','otpackage');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        //unauthorise_permission('131','786');
        $data['page_title'] = 'Dialysis Package List'; 
        $this->load->view('dialysis_pacakge_management/list',$data);
    }

    public function ajax_list()
    { 
        //unauthorise_permission('131','786');
        $users_data = $this->session->userdata('auth_users');
        $list = $this->otpackage->get_datatables();  
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
            $row[] = $ot->hours;
           // $row[] = $ot->type;  
            $row[] = $ot->days;
            $row[] = $ot->amount;
          //  $row[] = $ot->remarks;
            $row[] = $status;
          //  $row[] = date('d-M-Y H:i A',strtotime($ot->created_date)); 
           
          $btnedit='';
          $btndelete='';
          //if(in_array('788',$users_data['permission']['action'])){
               $btnedit = ' <a onClick="return edit_dialysis_pacakge('.$ot->id.');" class="btn-custom" href="javascript:void(0)" style="'.$ot->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
          //}
          // if(in_array('789',$users_data['permission']['action'])){
               $btndelete = ' <a class="btn-custom" onClick="return delete_dialysis_pacakge('.$ot->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
          //}
          $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->otpackage->count_all(),
                        "recordsFiltered" => $this->otpackage->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    

    
    public function add()
    {
      
        //unauthorise_permission('131','787');
        $data['page_title'] = "Add Dialysis Package";  
        $data['dialysis_type_list']=$this->otpackage->dialysis_type_list();
        //  print_r($data['ot_type_list']);die;
        $post = $this->input->post();
        //  print_r($post);
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'name'=>"",
                                  'dialysis_type_id'=>"",
                                  'amount'=>"",
                                  'days'=>"",
                                  'hours'=>"",
                                  'remarks'=>'',
                                  'pos_op_orders'=>'',
                                  'ot_details' => array(),
                                  'ot_procedure'=>'',
                                  'status'=>"1"
                                  );
        //print_r($data['form_data']);die;

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
              //echo "hi";die;
               $this->otpackage->save();
                echo 1;
                return false;
                
            }
            else
            {

                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('dialysis_pacakge_management/add',$data);       
    }

    public function edit($id="")
    {
      //echo 'dasd';die;
    // unauthorise_permission('131','788');

     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->otpackage->get_by_id($id);
        $result_ot_details = $this->otpackage->get_by_id_ot_details($id);
       //print_r($result_ot_details);die;
        $data['dialysis_type_list']=$this->otpackage->dialysis_type_list();
        $data['page_title'] = "Update Dialysis Package";  
        $post = $this->input->post();
       //print_r($post);die;
        
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'name'=>$result['name'], 
                                  'dialysis_type_id'=>$result['type'],
                                  'ot_details'=>$result_ot_details,
                                  'days'=>$result['days'],
                                  'ot_procedure'=>$result['ot_procedure'],
                                  'hours'=>$result['hours'],
                                  'amount'=>$result['amount'],
                                  'remarks'=>$result['remarks'],
                                  'status'=>$result['status']
                                  );  
       // print_r($data['form_data']);die;
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->otpackage->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
        //print_r($result_ot_details);die;
       $this->load->view('dialysis_pacakge_management/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();

        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('name', 'name', 'trim|required|callback_check_package'); 
        $this->form_validation->set_rules('dialysis_type_id', 'type', 'trim|required'); 
        $this->form_validation->set_rules('days', 'days', 'trim|required'); 
     //   $this->form_validation->set_rules('hours', 'hours', 'trim|required');
        $this->form_validation->set_rules('amount', 'amount', 'trim|required'); 
        if ($this->form_validation->run() == FALSE) 
        {  
              $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'name'=>$post['name'],
                                        'dialysis_type_id'=>$post['dialysis_type_id'],
                                       
                                        'amount'=>$post['amount'],
                                        'ot_procedure'=>$post['ot_procedure'],
                                        'days'=>$post['days'],
                                        'hours'=>$post['hours'],
                                        'remarks'=>$post['remarks'],
                                        'status'=>$post['status']
                                       ); 
             // print_r($data['form_data']);die;
            return $data['form_data'];
        }   
    }

    public function check_package($str){

        $post = $this->input->post();
        if(!empty($post['name']))
        {
          $this->load->model('general/general_model','general'); 
          if(!empty($post['data_id']) && $post['data_id']>0)
          {
          return true;
          }
        else
        {
            $packagedata = $this->general->check_ot_package($post['name']);
            if(empty($packagedata))
            {
            return true;
            }
            else
            {
            $this->form_validation->set_message('check_package', 'The  name already exists.');
            return false;
            }
        }  
        }
        else
        {
          $this->form_validation->set_message('check_package', 'The   name field is required.');
          return false; 
        } 
    }
 
    public function delete($id="")
    {
      //unauthorise_permission('131','789');
       if(!empty($id) && $id>0)
       {
           $result = $this->otpackage->delete($id);
           $response = "Dialysis package successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
       //unauthorise_permission('131','789');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->otpackage->deleteall($post['row_id']);
            $response = "Dialysis package successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->otpackage->get_by_id($id);  
        $data['page_title'] = $data['form_data']['name']." detail";
        $this->load->view('dialysis_pacakge_management/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
       //unauthorise_permission('131','790');
        $data['page_title'] = 'Dialysis Package Archive List';
        $this->load->helper('url');
        $this->load->view('dialysis_pacakge_management/archive',$data);
    }

    public function archive_ajax_list()
    {
        //unauthorise_permission('131','790');
        $this->load->model('dialysis_pacakge_management/dialysis_pacakge_management_archive_model','dialysis_pacakge_management_archive'); 

        $list = $this->dialysis_pacakge_management_archive->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $medicine) { 
            $no++;
            $row = array();
            if($medicine->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$medicine->id.'">'.$check_script; 
            $row[] = $medicine->name;
             $row[] = $medicine->hours;
          //  $row[] = $medicine->type;  
            $row[] = $medicine->days;
            $row[] = $medicine->amount;
          //  $row[] = $medicine->remarks;
            $row[] = $status;
           // $row[] = date('d-M-Y H:i A',strtotime($medicine->created_date)); 
            $users_data = $this->session->userdata('auth_users');
          $btnrestore='';
          $btndelete='';
          //if(in_array('792',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_dialysis_pacakge('.$medicine->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
          //}
          //if(in_array('791',$users_data['permission']['action'])){
               $btndelete = ' <a onClick="return trash('.$medicine->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
          //}
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->dialysis_pacakge_management_archive->count_all(),
                        "recordsFiltered" => $this->dialysis_pacakge_management_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        //unauthorise_permission('131','792');
          $this->load->model('dialysis_pacakge_management/dialysis_pacakge_management_archive_model','dialysis_pacakge_management_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->dialysis_pacakge_management_archive->restore($id);
           $response = "Dialysis package successfully restore in Dialysis package list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        //unauthorise_permission('131','792');
        $this->load->model('dialysis_pacakge_management/dialysis_pacakge_management_archive_model','dialysis_pacakge_management_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->dialysis_pacakge_management_archive->restoreall($post['row_id']);
            $response = "Dialysis package successfully restore in Dialysis package list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
      // unauthorise_permission('131','791');
         $this->load->model('dialysis_pacakge_management/dialysis_pacakge_management_archive_model','dialysis_pacakge_management_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->dialysis_pacakge_management_archive->trash($id);
           $response = "Dialysis package successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        //unauthorise_permission('131','791');
        $this->load->model('dialysis_pacakge_management/dialysis_pacakge_management_archive_model','dialysis_pacakge_management_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->dialysis_pacakge_management_archive->trashall($post['row_id']);
            $response = "Dialysis package successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function ot_summary_dropdown()
  {
      $dialysis_pacakge_management_list = $this->otpackage->dialysis_pacakge_management_list();
      $dropdown = '<option value="">Select Package</option>'; 
      if(!empty($dialysis_pacakge_management_list))
      {
        foreach($dialysis_pacakge_management_list as $otpackage)
        {
           $dropdown .= '<option value="'.$otpackage->id.'">'.$otpackage->name.'</option>';
        }
      } 
      echo $dropdown; 
  }

  function get_vals($vals="")
    { 
       //echo json_encode($vals);die;
        if(!empty($vals))
        {
            $result = $this->otpackage->get_vals($vals);  
            if(!empty($result))
            {
              echo json_encode($result,true);
            } 
        } 
    }
    
     // this function is used for sample excel sheet pacakge list on 23-04-2018
    public function sample_import_dialysis_pacakge_management_excel()
    {
            //unauthorise_permission(97,627);
            // Starting the PHPExcel library
        
            $this->load->library('excel');
            $this->excel->IO_factory();
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
            $objPHPExcel->setActiveSheetIndex(0);
            // Field names in the first row
            $fields = array('Package Name*','Type*','Days*','Hours*','Amount *','Procedure','Remarks');

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
            header("Content-Disposition: attachment; filename=dialysis_pacakge_management_import_sample_".time().".xls");  
            header("Pragma: no-cache"); 
            header("Expires: 0");
            ob_end_clean();
            $objWriter->save('php://output');
           
    }


    // this function is used for import excel sheet pacakge list on 23-04-2018
     public function import_dialysis_pacakge_management_excel()
    {
        //unauthorise_permission(97,628);
        $this->load->library('excel');
        $data['page_title'] = 'Import OT pacakge Excel';
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
                    
                   $array_keys = array('name','type','days','hours','amount','ot_procedure','remarks');

                    $count_array_keys = count($array_keys);
                    $array_values_keys = array('A','B','C','D','E','F','G');
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
                    $this->otpackage->save_all_dialysis_pacakge_management($ot_all_data);
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

        $this->load->view('dialysis_pacakge_management/import_dialysis_pacakge_management_excel',$data);
    }

}
?>