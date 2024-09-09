<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Path_profile_panel_price extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('path_profile_panel_price/path_profile_panel_price_model','path_profile_panel_price');
        $this->load->library('form_validation');
    }

    public function index()
    { 
      
        $data['page_title'] = 'Profile Panel Price'; 
        $data['form_data']=array('data_id'=>'');
        $this->load->view('path_profile_panel_price/path_profile_panel_price_list',$data);
    }

	public function get_panel_list()
	{
	 $dropdown='';
	 //$this->session->unset_userdata('master_test_head');
	 $users_data = $this->session->userdata('auth_users');
	 $this->load->model('general/general_model'); 
	 $post = $this->input->post();
		$this->load->model('path_panel_price/Path_panel_price_model','path_panel_price');
		$data['insurance_company_list'] = $this->general_model->insurance_company_list($users_data['parent_id']);
		if(!empty($data['insurance_company_list'])){
				$dropdown = '<label class="patient_sub_branch_label">Select Panel</label> <select id="paneln_ids"  onchange="get_profile_list(this.value);"><option value="">Select Panel</option>';
			 foreach($data['insurance_company_list'] as $company_list)
                              {
                               $dropdown.='<option value="'.$company_list->id.'">'.$company_list->insurance_company.'</option>'; 
                              }	
                              $dropdown.='</select>';			

			}
			echo $dropdown;
	}

     public function get_profile_list()
     {
          $post = $this->input->post(); 
          $this->session->set_userdata('panelPriceIDs',$post['paneln_ids']);
          
          $data['page_title'] = 'Profile Pannel Price'; 
          $formated_data='';
          if(!empty($post['doctors_id']) || !empty($post['branch_id']))
          {
              $result = $this->path_profile_panel_price->get_profile_list(); 
              if(!empty($result))
              { 
                 $formated_data = $this->get_format_table($result,$post); 
              }
              else
              {
                $formated_data='<tr><td colspan="6" align="center"><font color="#b64a30">Profile not available.</font></td></tr>';
              }
          }
          else
          {
             $formated_data='<tr><td colspan="6" align="center"><font color="#b64a30">Profile not available.</font></td></tr>';
          } 
          echo $formated_data;
     }


    public function path_ajax_list()
    {  
     
      //  $test_master_head = $this->session->userdata('master_test_head');
      //  print_r()
        $users_data = $this->session->userdata('auth_users');
        $list = [];
         $paneln_ids='';
         //$dept_ids='';

        // if(isset($test_master_head) && !empty($test_master_head))
        // {
          $list = $this->path_profile_panel_price->get_datatables('');  
         //echo "<pre>" ;print_r($list);die;
        //}
        if(!empty($_POST['paneln_ids']) && isset($_POST['paneln_ids']))
        {
          $paneln_ids= $_POST['paneln_ids'];
        }
    
       // print_r($list);die;
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $btn_save='';
        $total_num = count($list);
        foreach ($list as $profile_master) {
          
            $no++;
            $row = array(); 
             $row[] = '<input type="checkbox" name="profile_id['.$i.'][profile_id]" class="checklist" value="'.$profile_master->id.'">';
            $row[] = $profile_master->profile_name;   
            $row[] = '<input type="hidden" name="paneln_ids" id="panel_id" value="'.$paneln_ids.'"/> <input type="text" name="profile_id['.$i.'][master_rate]"  id="master_price_'.$i.'"  value="'.$profile_master->master_rate.'"/><input type="hidden" name="profile_id['.$i.'][profiles_id]"  value="'.$profile_master->id.'"/>
            
            ';
            $row[] = '<input type="hidden" name="paneln_ids" id="panel_id" value="'.$paneln_ids.'"/> <input type="text" name="profile_id['.$i.'][base_rate]"  id="base_price_'.$i.'"  value="'.$profile_master->base_rate.'"/>';
               if(in_array('1229',$users_data['permission']['action']))
               {
                    $btn_save='<button type="button" class="btn-custom" data-paneln_ids="paneln_ids"  data-id="'.$i.'"
                  data-profile_id="'.$profile_master->id.'" id="save_profile" onclick="save_panel_rate(this);">Save</button></a>
                ';
               } 
               
                      $row[] = $btn_save;
                              
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                       "recordsTotal" => $this->path_profile_panel_price->count_all('',$profile_id),
                        "recordsFiltered" => $this->path_profile_panel_price->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
          
    } 

    
    

     private function calc_string( $mathString )
      {
              $cf_DoCalc = create_function("", "return (" . $mathString . ");" );
             
              return $cf_DoCalc();
      }

     public function save_panel_rate()
     {
     
          $post = $this->input->post();
         // print_r($post);die;
          $data['page_title'] = " Profile Panel Price";
          $msg='';
          if(isset($post) && !empty($post))
          { 
            if(empty($post['paneln_ids']))
            {
                 //$this->session->set_flashdata('error','Please select panel.');
                 $msg=1;
            }
            else
            {
              $this->path_profile_panel_price->save_panel_rate();
              $msg=2;
            }
          	
          }
         echo $msg;

     }

  public function save_price_list()
  {
    $post = $this->input->post();
   if(isset($post) )
    {  
      $msg='';
      if(empty($post['paneln_ids']))
      {
       // echo 1;die;
          // $this->session->set_flashdata('error','Please select panel.');
           $msg='1';
      }
      else if(empty($post['profile_id']))
      {
       // echo 1;die;
          // $this->session->set_flashdata('error','Please select panel.');
           $msg='1';
      }
      else
      {
        $this->path_profile_panel_price->save_panel_all_rate();
        $msg= 2;
      }
       
    }
      echo $msg;
  } 
  
  
  public function get_profile_panel_excel()
    {
        $this->load->model('general/general_model');
        // Starting the PHPExcel library
        $this->load->library('excel');
        $this->excel->IO_factory();
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
        $objPHPExcel->setActiveSheetIndex(0);
        // Field names in the first row
        $fields = array('Profile Name','Profile Print Name','Panel Name','Profile Panel ID','Profile ID','Panel ID','Panel Rate');
          $col = 0;
        foreach ($fields as $field)
        {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
            $col++;
        }
        $panelPriceIDs = $this->session->userdata('panelPriceIDs');
        $panel_name = $this->path_profile_panel_price->get_panel_name($panelPriceIDs);
        $list = $this->path_profile_panel_price->search_profile_list($panelPriceIDs);
        //echo '<pre>'; print_r($list);die;
        $rowData = array();
        $data= array();
        if(!empty($list))
        {
           
            $i=0; 
            foreach($list as $test_master)
            {       
                array_push($rowData,$test_master->profile_name,$test_master->print_name,$panel_name,$test_master->id,$test_master->profileID,$panelPriceIDs,$test_master->master_rate);
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
              foreach($data as $doctors_data)
              {
                   $col = 0;
                   foreach ($fields as $field)
                   { 
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $doctors_data[$field]);
                        $col++;
                   }
                   $row++;
              }
              $objPHPExcel->setActiveSheetIndex(0);
              $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
          }
          
          // Sending headers to force the user to download the file
          header('Content-Type: application/octet-stream charset=UTF-8');
          header("Content-Disposition: attachment; filename=export_profile_master_".time().".xls");
         header("Pragma: no-cache"); 
         header("Expires: 0");
         if(!empty($data))
         {
            ob_end_clean();
            $objWriter->save('php://output');
        }
    }
    
  public function import_update_profile_panel_excel()
  {
        //unauthorise_permission(97,628);
        $this->load->library('excel');
        $data['page_title'] = 'Import Updated Profile Panel Price excel';
        $arr_data = array();
        $header = array();
        $path='';
        //print_r($_FILES); die;
        if(isset($_FILES) && !empty($_FILES))
        {
          
            $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
            if(!isset($_FILES['test_list']) || $_FILES['test_list']['error']>0)
            {
               
               $this->form_validation->set_rules('test_list', 'file', 'trim|required');  
            } 
            $this->form_validation->set_rules('name', 'name', 'trim|required');  
            if($this->form_validation->run() == TRUE)
            {
               //echo DIR_UPLOAD_PATH.'import_master/'; die;
                $config['upload_path']   = DIR_UPLOAD_PATH.'temp/'; 
                $config['allowed_types'] = '*'; 
                $this->load->library('upload', $config);
                if(!$this->upload->do_upload('test_list')) 
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
                    $arrs_data = array_values($arr_data);
                    $total_test = count($arrs_data);
                    
                   $array_keys = array('profile_name','print_name','panel_name','profile_panel_id','profile_id','panel_id','master_rate');

                    $count_array_keys = count($array_keys);
                    $array_values_keys = array('A','B','C','D','E','F','G');
                    $patient_data = array();
                    
                    $j=0;
                    $m=0;
                    $data='';
                    $test_all_data= array();
                    for($i=0;$i<$total_test;$i++)
                    {
                        $med_data_count_values[$i] = count($arrs_data[$i]);
                        for($p=0;$p<$count_array_keys;$p++)
                        {
                            if(array_key_exists($array_values_keys[$p],$arrs_data[$i]))
                            {
                                $test_all_data[$i][$array_keys[$p]] = $arrs_data[$i][$array_values_keys[$p]];
                            }
                            else
                            {
                                $test_all_data[$i][$array_keys[$p]] ="";
                            }
                        }
                    } 
                    //echo "<pre>";print_r($test_all_data); exit;
                    $this->path_profile_panel_price->udpate_all_profile_panel($test_all_data);
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

        $this->load->view('path_profile_panel_price/import_update_profile_panel_excel',$data);
    }

   
 
}
?>