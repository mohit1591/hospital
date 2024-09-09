<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Amb_particular extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('ambulance/particular_model','particular');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('404','2449');
        $data['page_title'] = 'Miscellaneous List'; 
        $this->load->view('ambulance/amb_particular/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission('404','2449');
        $list = $this->particular->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $particular) {
         // print_r($relation);die;
            $no++;
            $row = array();
            if($particular->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$particular->id.'">'.$check_script; 
            $row[] = $particular->particular;
            $row[] = $particular->charge;    
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($particular->created_date)); 
            $users_data = $this->session->userdata('auth_users');
          $btnedit='';
          $btndelete='';
          if(in_array('2451',$users_data['permission']['action'])){
               $btnedit = ' <a onClick="return edit_particular('.$particular->id.');" class="btn-custom" href="javascript:void(0)" style="'.$particular->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
          }
           if(in_array('2452',$users_data['permission']['action'])){
               $btndelete = ' <a class="btn-custom" onClick="return delete_particular('.$particular->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
          }
          $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->particular->count_all(),
                        "recordsFiltered" => $this->particular->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
        unauthorise_permission('404','2450');
        $data['page_title'] = "Add Miscellaneous";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'particular'=>"",
                                  'charge'=>"",
                                  'status'=>"1"
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate('');
            if($this->form_validation->run() == TRUE)
            {
                $this->particular->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('ambulance/amb_particular/add',$data);       
    }
    
    public function edit($id="")
    {
      unauthorise_permission('404','2451');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->particular->get_by_id($id);  
        $data['page_title'] = "Update Miscellaneous";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'particular'=>$result['particular'], 
                                  'charge'=>$result['charge'],
                                  'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate($id);
            if($this->form_validation->run() == TRUE)
            {
                $this->particular->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('ambulance/amb_particular/add',$data);       
      }
    }
     
    private function _validate($id='')
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('particular', 'particular', 'trim|required|callback_check_unique_value['.$id.']'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'particular'=>$post['particular'],
                                        'charge'=>$post['charge'], 
                                        'status'=>$post['status']
                                       ); 
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {
       unauthorise_permission('404','2452');
       if(!empty($id) && $id>0)
       {
           $result = $this->particular->delete($id);
           $response = "Miscellaneous successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
       unauthorise_permission('404','2452');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->particular->deleteall($post['row_id']);
            $response = "Miscellaneous successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->particular->get_by_id($id);  
        $data['page_title'] = $data['form_data']['particular']." detail";
        $this->load->view('ambulance/amb_particular/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission('404','2452');
        $data['page_title'] = 'Miscellaneous Archive List';
        $this->load->helper('url');
        $this->load->view('ambulance/amb_particular/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('404','2452');
        $this->load->model('ambulance/particular_archive_model','particular_archive'); 

        $list = $this->particular_archive->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $particular) { 
            $no++;
            $row = array();
            if($particular->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$particular->id.'">'.$check_script; 
            $row[] = $particular->particular;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($particular->created_date)); 
            $users_data = $this->session->userdata('auth_users');
          $btnrestore='';
          $btndelete='';
          if(in_array('2452',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_particular('.$particular->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
          }
          if(in_array('2452',$users_data['permission']['action'])){
               $btndelete = ' <a onClick="return trash('.$particular->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
          }
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->particular_archive->count_all(),
                        "recordsFiltered" => $this->particular_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission('404','2452');
        $this->load->model('ambulance/particular_archive_model','particular_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->particular_archive->restore($id);
           $response = "Miscellaneous successfully restore in Miscellaneous list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission('404','2452');
        $this->load->model('ambulance/particular_archive_model','particular_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->particular_archive->restoreall($post['row_id']);
            $response = "Miscellaneous successfully restore in Miscellaneous list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('404','2452');
        $this->load->model('ambulance/particular_archive_model','particular_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->particular_archive->trash($id);
           $response = "Miscellaneous successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('404','2452');
        $this->load->model('ambulance/particular_archive_model','particular_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->particular_archive->trashall($post['row_id']);
            $response = "Miscellaneous successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function particular_dropdown()
  {

      $particular_list = $this->particular->particular_list();
      $dropdown = '<option value="">Select Particular</option>'; 
      if(!empty($particular_list))
      {
        foreach($particular_list as $particular)
        {
           $dropdown .= '<option value="'.$particular->id.'">'.$particular->particular.'</option>';
        }
      } 
      echo $dropdown; 
  }
  
  // this function is used for sample excel sheet doctor list on 23-04-2018
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
     public function import_particular_excel()
    {
        //unauthorise_permission(97,628);
        $this->load->library('excel');
        $data['page_title'] = 'Import Miscellaneous excel';
        $arr_data = array();
        $header = array();
        $path='';

      //echo"hello";print_r($_FILES); 
        if(isset($_FILES) && !empty($_FILES))
        {
          
            $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
            if(!isset($_FILES['particular_list']) || $_FILES['particular_list']['error']>0)
            {
               
               $this->form_validation->set_rules('particular_list', 'file', 'trim|required');  
            } 
            $this->form_validation->set_rules('name', 'name', 'trim|required');  
            if($this->form_validation->run() == TRUE)
            {
               
                $config['upload_path']   = DIR_UPLOAD_PATH.'patients/'; 
                $config['allowed_types'] = '*'; 
                $this->load->library('upload', $config);
                if(!$this->upload->do_upload('particular_list')) 
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
                    $total_paticular = count($arrs_data);
                    
                   $array_keys = array('particular','charge');

                    $count_array_keys = count($array_keys);
                    $array_values_keys = array('A','B');
                    $paticular_data = array();
                    
                    $j=0;
                    $m=0;
                    $data='';
                    $particular_all_data= array();
                    for($i=0;$i<$total_paticular;$i++)
                    {
                        $med_data_count_values[$i] = count($arrs_data[$i]);
                        for($p=0;$p<$count_array_keys;$p++)
                        {
                            if(array_key_exists($array_values_keys[$p],$arrs_data[$i]))
                            {
                                $particular_all_data[$i][$array_keys[$p]] = $arrs_data[$i][$array_values_keys[$p]];
                            }
                            else
                            {
                                $particular_all_data[$i][$array_keys[$p]] ="";
                            }
                        }
                    }

                   // echo '<pre>'; print_r($doctor_all_data); exit;
                    $this->particular->save_all_particular($particular_all_data);
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

        $this->load->view('ambulance/amb_particular/import_particular_excel',$data);
    } 

  // op 19/08/19
  function check_unique_value($advice, $id='') 
  {     
        $users_data = $this->session->userdata('auth_users');
        $result = $this->particular->check_unique_value($users_data['parent_id'], $particular,$id);
        if($result == 0)
            $response = true;
        else {
            $this->form_validation->set_message('check_unique_value', 'Miscellaneous already exist.');
            $response = false;
        }
        return $response;
    }

}
?>