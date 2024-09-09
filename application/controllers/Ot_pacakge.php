<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ot_pacakge extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('ot_pacakge/ot_pacakge_model','otpackage');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('131','786');
        $data['page_title'] = 'Operation Package List'; 
        $this->load->view('ot_pacakge/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission('131','786');
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
          if(in_array('788',$users_data['permission']['action'])){
               $btnedit = ' <a onClick="return edit_ot_pacakge('.$ot->id.');" class="btn-custom" href="javascript:void(0)" style="'.$ot->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
          }
           if(in_array('789',$users_data['permission']['action'])){
               $btndelete = ' <a class="btn-custom" onClick="return delete_ot_pacakge('.$ot->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
          }
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
      
        unauthorise_permission('131','787');
        $data['page_title'] = "Add Operation Package";  
        $data['ot_type_list']=$this->otpackage->type_list();
      //  print_r($data['ot_type_list']);die;
        $post = $this->input->post();
     //  print_r($post);
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'name'=>"",
                                  //'type'=>"",
                                  'ot_pacakge_type_id'=>"",
                                 

                                //  'ot_type_id'=>"",
                                  'amount'=>"",
                                  'days'=>"",
                                  'hours'=>"",
                                  'remarks'=>'',
                                  'pos_op_orders'=>'',
                                  'ot_details' => array(),
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
       $this->load->view('ot_pacakge/add',$data);       
    }

    public function edit($id="")
    {
      //echo 'dasd';die;
     unauthorise_permission('131','788');

     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->otpackage->get_by_id($id);
        $result_ot_details = $this->otpackage->get_by_id_ot_details($id);
       //print_r($result_ot_details);die;
        $data['ot_type_list']=$this->otpackage->type_list();
        $data['page_title'] = "Update Operation Package";  
        $post = $this->input->post();
       //print_r($post);die;
        
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'name'=>$result['name'], 
                                  //'type'=>$result['operation_type'],
                                  'ot_pacakge_type_id'=>$result['type'],
                                  'ot_details'=>$result_ot_details,
                                  'days'=>$result['days'],
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
       $this->load->view('ot_pacakge/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();

        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('name', 'name', 'trim|required|callback_check_package'); 
        $this->form_validation->set_rules('ot_pacakge_type_id', 'type', 'trim|required'); 
        $this->form_validation->set_rules('days', 'days', 'trim|required'); 
     //   $this->form_validation->set_rules('hours', 'hours', 'trim|required');
        $this->form_validation->set_rules('amount', 'amount', 'trim|required'); 
        if ($this->form_validation->run() == FALSE) 
        {  
              $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'name'=>$post['name'],
                                        'ot_pacakge_type_id'=>$post['ot_pacakge_type_id'],
                                       
                                        'amount'=>$post['amount'],
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
      unauthorise_permission('131','789');
       if(!empty($id) && $id>0)
       {
           $result = $this->otpackage->delete($id);
           $response = "Operation package successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
       unauthorise_permission('131','789');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->otpackage->deleteall($post['row_id']);
            $response = "Operation package successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->otpackage->get_by_id($id);  
        $data['page_title'] = $data['form_data']['name']." detail";
        $this->load->view('ot_pacakge/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
       unauthorise_permission('131','790');
        $data['page_title'] = 'Operation Package Archive List';
        $this->load->helper('url');
        $this->load->view('ot_pacakge/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('131','790');
        $this->load->model('ot_pacakge/ot_pacakge_archive_model','ot_pacakge_archive'); 

        $list = $this->ot_pacakge_archive->get_datatables();  
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
          if(in_array('792',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_ot_pacakge('.$medicine->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
          }
          if(in_array('791',$users_data['permission']['action'])){
               $btndelete = ' <a onClick="return trash('.$medicine->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
          }
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->ot_pacakge_archive->count_all(),
                        "recordsFiltered" => $this->ot_pacakge_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission('131','792');
          $this->load->model('ot_pacakge/ot_pacakge_archive_model','ot_pacakge_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->ot_pacakge_archive->restore($id);
           $response = "Operation package successfully restore in Operation package list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission('131','792');
        $this->load->model('ot_pacakge/ot_pacakge_archive_model','ot_pacakge_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->ot_pacakge_archive->restoreall($post['row_id']);
            $response = "Operation package successfully restore in Operation package list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
       unauthorise_permission('131','791');
         $this->load->model('ot_pacakge/ot_pacakge_archive_model','ot_pacakge_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->ot_pacakge_archive->trash($id);
           $response = "Operation package successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('131','791');
        $this->load->model('ot_pacakge/ot_pacakge_archive_model','ot_pacakge_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->ot_pacakge_archive->trashall($post['row_id']);
            $response = "Operation package successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function ot_summary_dropdown()
  {
	  $ot_pacakge_list = $this->otpackage->ot_pacakge_list();
      $dropdown = '<option value="">Select Operation Package</option>'; 
      if(!empty($ot_pacakge_list))
      {
        foreach($ot_pacakge_list as $otpackage)
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
      public function sample_import_ot_pacakge_excel()
    {
            //unauthorise_permission(97,627);
            // Starting the PHPExcel library
        
            $this->load->library('excel');
            $this->excel->IO_factory();
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
            $objPHPExcel->setActiveSheetIndex(0);
            // Field names in the first row
            $fields = array('Operation Package Name*','Operation Type*','Operation Days*','Operation Hours*','Amount *','Operation Procedure','Remarks');

            
      
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
            header("Content-Disposition: attachment; filename=ot_pacakge_import_sample_".time().".xls");  
            header("Pragma: no-cache"); 
            header("Expires: 0");
            ob_end_clean();
            $objWriter->save('php://output');
           
    }


    // this function is used for import excel sheet pacakge list on 23-04-2018
     public function import_ot_pacakge_excel()
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
                    $this->otpackage->save_all_ot_pacakge($ot_all_data);
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

        $this->load->view('ot_pacakge/import_ot_pacakge_excel',$data);
    }

}
?>