<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Donor extends CI_Controller 
{
 
	function __construct() 
	{
		parent::__construct();	
		auth_users();  
    $this->load->model('blood_bank/donor/donor_model','donor'); 
    $this->load->library('form_validation');
  }

  public function index()
  {
    unauthorise_permission(265,1532);
    $this->session->unset_userdata('donor_search');
        // Default Search Setting
    $this->load->model('blood_bank/general/blood_bank_general_model','blood_general_model');
    $data['blood_groups']=$this->blood_general_model->get_blood_group_list();
        $this->load->model('default_search_setting/default_search_setting_model'); 
        $default_search_data = $this->default_search_setting_model->get_default_setting();
        if(isset($default_search_data[1]) && !empty($default_search_data) && $default_search_data[1]==1)
        {
            $start_date = '';
            $end_date = '';
            $qc_result=1;
        }
        else
        {
            $start_date = date('d-m-Y');
            $end_date = date('d-m-Y');
             $qc_result=1;
        }
        // End Defaul Search

         $data['form_data'] = array('donor_id'=>'','donor_name'=>'','blood_group'=>'','qc_result'=>'','start_date'=>$start_date, 'end_date'=>$end_date,'outcome'=>'');




    $data['page_title'] = 'Donor List'; 
    $this->load->view('blood_bank/donor/list',$data);
  }

  // Function to show list of blood Groups starts here
  public function ajax_list()
  {
    unauthorise_permission(265,1532);
    $users_data = $this->session->userdata('auth_users');
    $list = $this->donor->get_datatables();  
   // print_r($list);die;
    $data = array();
    $no = $_POST['start'];
    $i = 1;
    $total_num = count($list);
    foreach ($list as $donor) 
    {
        
        $hide_button = $this->donor->get_examination_hide($donor->id);
      $no++;
        $row = array();
        if($donor->status==1)
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
        $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$donor->id.'">'.$check_script; 
        $row[] = $donor->donor_code;  
        $row[] = $donor->donor_name;  
      //  $row[] = $donor->relation." ".$donor->relation_name;  
        $row[] = $donor->mobile_no;
        $row[] = $donor->gender;
        $row[] = hms_patient_age_calulator($donor->age_y,$donor->age_m,$donor->age_d);
       // $row[] = $donor->address;
        $row[] = $donor->blood_group; 
        //$row[] = $donor->mode_of_donation; 
        //$row[] = ( strtotime($donor->previous_donation_date) > 0 ? date('d-M-Y', strtotime($donor->previous_donation_date)) : ''); 
        //$row[] = $donor->preferred_reminder_service;
        //$row[] = $donor->height;
        $row[] = ($donor->weight > 0 ? $donor->weight : '') ;
        $row[] = $donor->remark;
        
        if($hide_button=="empty")
        {
            
            $done_status = '<font color="red">Pending</font>';
        }   
        else{
            $done_status = '<font color="green">Done</font>';
        }
        
        if($users_data['parent_id']=='190' && $donor->id<=8061)
        {
            $done_status = '<font color="green">Done</font>';
        }

        $row[] = $done_status;
        $row[] = date('d-M-Y H:i A',strtotime($donor->created_date)); 
       
      $btnedit='';
      $btndelete='';
      $actions='';
      $btnView='';
      if(in_array('1534',$users_data['permission']['action']))
      {
           $btnedit = ' <a onClick="return edit_donor_details('.$donor->id.');" class="btn-custom" href="javascript:void(0)" style="'.$donor->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
      }
      if(in_array('1535',$users_data['permission']['action']))
      {
           $btndelete = ' <a class="btn-custom" onClick="return delete_donor('.$donor->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
      }
      
$actions="";
     if(in_array('1541',$users_data['permission']['action']))
      {
         $hide_button = $this->donor->get_examination_hide($donor->id);
       //print_r($hide_button);
         if($hide_button=="empty")
         {

         $actions.='<a class="btn-custom" onClick="return add_examination_details('.$donor->id.');" href="javascript:void(0)" title="Examinations" data-url="512"><i class="fa fa-patient"></i> Examinations </a>';
         
         
         $print_certi_url = "'".base_url('blood_bank/donor/print_certificate/'.$donor->id)."'";
        $btn_cert_print = '<a class="btn-custom" href="javascript:void(0)" onClick="return print_window_page('.$print_certi_url.')"  title="Print" ><i class="fa fa-print"></i> Print Certificate </a>';

         }


    }
    
     
        
        
      


     if(in_array('1538',$users_data['permission']['action']))
      {
      $btnView.='<a class="btn-custom" onClick="return view_donor_details('.$donor->id.');" href="javascript:void(0)" title="View" data-url="512"><i class="fa fa-eye"></i> View </a>';
      }
      
      $row[] = $btnedit.$btndelete.$actions.$btnView.$btn_cert_print;
      $data[] = $row;
      $i++;
    }

    $output = array(
                    "draw" => $_POST['draw'],
                    "recordsTotal" => $this->donor->count_all(),
                    "recordsFiltered" => $this->donor->count_filtered(),
                    "data" => $data,
            );
    echo json_encode($output);
  }
  // Function to show list of blood Groups Ends here 

  // Function to open add form starts here
  public function add($donor_id="")
  {
    unauthorise_permission('265','1533');
    if($donor_id!="")
    {
      $data['donor_data']   = $this->donor->get_by_id($donor_id);
      $data['donor_id']     = $data['donor_data']['id'];
      $data['reg_no']     = $data['donor_data']['donor_code'];
      $data['start_time']=  $data['donor_data']['start_time'];

      $data['marital_status']=$data['donor_data']['marital_status'];
    }  
    else
    {
      $data['start_time']=date('H:i:s');
      $data['reg_no']=generate_unique_id(41);
      $data['donor_data']   = "empty";
      $data['donor_id']     = "0";
      $data['marital_status']     = "0";
    }
    $users_data = $this->session->userdata('auth_users');
    $branch_id=$users_data['parent_id'];
    $this->load->model('general/general_model','general_model');
    $this->load->model('blood_bank/general/blood_bank_general_model','blood_general_model');
    $data['page_title']="Donor Details";
    $data['simulation_list'] = $this->general_model->simulation_list($branch_id); 
    $data['country_list'] = $this->general_model->country_list();
    $data['gardian_relation_list'] = $this->general_model->gardian_relation_list();

    $data['reminder_service']=$this->blood_general_model->get_reminder_services_list();
    $data['blood_groups']=$this->blood_general_model->get_blood_group_list();
    $data['modes_of_donation']=$this->blood_general_model->get_mode_of_donation_list();
    $data['camp_list']=$this->blood_general_model->get_blood_camp_list();

     $this->load->view('blood_bank/donor/add',$data);
  }
  // function to open add form ends here
      

  public function save_donor()
  {
    $response = $this->_validate();

    if($response!=200)
    {
      echo $response;
    }
    else
    {
      $this->load->model('blood_bank/general/blood_bank_general_model','blood_general_model');
      $users_data = $this->session->userdata('auth_users');
      $branch_id=$users_data['parent_id'];
      $donor_id=$this->input->post('donor_id');
      $anniversary='';
      $start_time='';
      $other_id='';
      $other_post='';
     
      if(!empty($this->input->post('anniversary')))
      {
       $anniversary = date('Y-m-d', strtotime($this->input->post('anniversary')));  
      }
      if(!empty($this->input->post('start_time')))
      {
      	$start_time=$this->input->post('start_time');
      }
      else
      {
      	$start_time=date('H:i:s');
      }
      if(!empty($this->input->post('other_id')))
      {
      	$other_id=$this->input->post('other_id');

      }
      else
      {
      	$other_id='';
      }
     if(!empty($this->input->post('other_id')))
     {
        $other_post=$this->input->post('other_post');
     }
     else
     {
       $other_post='';
     }

      $donor_data_array=array(
                           'simulation_id'=>$this->input->post('simulation_id'),
                           'donor_name'=>$this->input->post('donor_name'),
                           'relation_type'=>$this->input->post('relation_type'),
                           'relation_simulation_id'=>$this->input->post('relation_simulation_id'),
                           'relation_name'=>$this->input->post('relation_name'),
                           'mobile_no'=>$this->input->post('mobile_no'),
                           'age_y'=>$this->input->post('age_y'),
                           'age_m'=>$this->input->post('age_m'),
                           'age_d'=>$this->input->post('age_d'),
                           'donor_email'=>$this->input->post('donor_email'),
                           'address'=>$this->input->post('address'),
                           'address2'=>$this->input->post('address_second'),
                           'address3'=>$this->input->post('address_third'),
                           'pincode'=>$this->input->post('pincode'),
                           'marital_status'=>$this->input->post('marital_status'),
                           'anniversary'=>$anniversary,
                           'dob'=>date('Y-m-d', strtotime($this->input->post('dob'))),
                           'country_id'=>$this->input->post('country_id'),
                           'state_id'=>$this->input->post('state_id'),
                           'city_id'=>$this->input->post('citys_id'),
                           'gender'=>$this->input->post('gender'),
                           'start_time'=>$start_time,
                           'post_name_id'=>$this->input->post('post_name_id'),
                           'other_post'=>$other_post,
                           'other_id'=>$other_id,
                           'reminder_service_id'=>$this->input->post('reminder_service'),
                           'blood_group_id'=>$this->input->post('blood_group_id'),
                           'mode_of_donation'=>$this->input->post('mode_of_donation'),
                           'camp_id'=>$this->input->post('camp_id'),
                           'previous_donation_date'=>date('Y-m-d',strtotime($this->input->post('previous_donation_date'))),
                           'registration_date'=>date('Y-m-d'),
                           'height'=>$this->input->post('height'),
                           'weight'=>$this->input->post('weight'),
                           'remark'=>$this->input->post('remark'),
                           'status'=>'1',
                           'ip_address'=>$_SERVER['REMOTE_ADDR'],
                           );
      //print_r($donor_data_array);
      if($donor_id > 0)
      {
        
        $donor_condition=" id=".$donor_id." and branch_id=".$branch_id." ";
        $donor_data_array["modified_by"]=$users_data['id'];
        $donor_data_array["modified_date"]=date('Y-m-d H:i:s');
        
        /* image uplode*/
        //print_r($_POST);die;
        $file_name2='';
           if(!empty($_FILES['photo']['name']))
                { 

                    $config['upload_path']   = DIR_UPLOAD_PATH.'blood_bank/donor_profile/'; 
                    $config['allowed_types'] = 'jpg|png|jpeg|jpg'; 
                    $config['max_size']      = 1000; 
                    $config['encrypt_name'] = TRUE; 
                    $this->load->library('upload', $config);
                    //print_r($config);die;
                    if($this->upload->do_upload('photo')) 
                    {
                     
                    $file_data = $this->upload->data(); 
                    $file_name1= $file_data['file_name'];
                    }
              
                }
                else
                {
                  $file_name1=$this->input->post('old_img');
                }

                 if(!empty($_FILES['general_form']['name']))
      { 
        
        $config['upload_path']   = DIR_UPLOAD_PATH.'blood_bank/donor_profile/'; 
        $config['allowed_types'] = 'jpg|png|jpeg|jpg'; 
        $config['max_size']      = 1000; 
        $config['encrypt_name'] = TRUE; 
        $this->load->library('upload', $config);
      //print_r($config);die;
        if($this->upload->do_upload('general_form')) 
        {

          $file_data = $this->upload->data(); 
          $file_name2= $file_data['file_name'];
        }

        }
        else
        {
          $file_name2=$this->input->post('old_general_form'); 
        }

         /* image uplode*/
         $donor_data_array["profile_image"]=$file_name1;
          $donor_data_array["document_name"]=$file_name2;
         $donor_rec_id=$this->blood_general_model->common_update('hms_blood_donor',$donor_data_array, $donor_condition);
        echo json_encode(array('st'=>1, 'msg'=>'Donor updated successfully'));
      }
      else
      {

        $reg_no = generate_unique_id(41);
        $donor_data_array['donor_code']=$reg_no;
        $donor_data_array["branch_id"]=$branch_id;
        $donor_data_array["created_by"]=$users_data['id'];
        $donor_data_array["created_date"]=date('Y-m-d H:i:s');

         /* image uplode*/

      if(!empty($_FILES['photo']['name']))
                { 

                    $config['upload_path']   = DIR_UPLOAD_PATH.'blood_bank/donor_profile/'; 
                    $config['allowed_types'] = 'jpg|png|jpeg|jpg'; 
                    $config['max_size']      = 1000; 
                    $config['encrypt_name'] = TRUE; 
                    $this->load->library('upload', $config);
                    //print_r($config);die;
                    if($this->upload->do_upload('photo')) 
                    {
                     
                    $file_data = $this->upload->data(); 
                    $file_name1= $file_data['file_name'];
                    }
              
                }
                else
                {
                  $file_name1=''; 
                }
       if(!empty($_FILES['general_form']['name']))
                  { 
                    
                    $config['upload_path']   = DIR_UPLOAD_PATH.'blood_bank/donor_profile/'; 
                    $config['allowed_types'] = 'jpg|png|jpeg|jpg'; 
                    $config['max_size']      = 1000; 
                    $config['encrypt_name'] = TRUE; 
                    $this->load->library('upload', $config);
                  //print_r($config);die;
                    if($this->upload->do_upload('general_form')) 
                    {

                      $file_data = $this->upload->data(); 
                      $file_name2= $file_data['file_name'];
                    }

                    }
                    else
                    {
                      $file_name2=''; 
                    }
                            
                     /* image uplode*/
                    $donor_data_array["profile_image"]=$file_name1;
                    $donor_data_array["document_name"]=$file_name2;
                    $donor_rec_id = $this->blood_general_model->common_insert('hms_blood_donor',$donor_data_array);
                    //echo $this->db->last_query();die;
                    echo json_encode(array('st'=>1, 'msg'=>'Donor inserted successfully'));
                  }
    }
  }

   public function calc_times()
  {
    if($this->input->post('flag')==1)
    {
        $nw=date('H:i:s');
        echo $nw;
    }
  }


  // Function to validate common settings
  public function common_setting_validation()
  {
    $this->load->model('blood_bank/general/blood_bank_general_model','blood_general_model');
    $value=$this->input->post('data');
    $flag=$this->input->post('flag');
    
      $status=1;
      if($flag==1)   // for minimum and maximum age
      {
        $common_settings=$this->blood_general_model->get_common_settings('DONAR_AGE_CRITERIA'); 
        $min_age=$common_settings[0]->setting_value1;
        $max_age=$common_settings[0]->setting_value2;
        if($min_age!=0)
        {
           if($value < $min_age)
             $status=0;
        }
        if($max_age!=0)
        {
            if($value > $max_age)
              $status=0;
        }
        if($status==0)
          echo json_encode(array('st'=>0,'msg'=>'<font style="color:red;">Age is not valid</font>')); 
        else
          echo json_encode(array('st'=>1,'msg'=>'')); 
      }  
      else if($flag==2)   // for minimum weight
      {
        $common_settings=$this->blood_general_model->get_common_settings('DONAR_MIN_W_CR');
        $minimum_weight=$common_settings[0]->setting_value1;
        if($value < $minimum_weight)
          echo json_encode(array('st'=>0,'msg'=>'<font style="color:red;">Minimum weight is not valid</font>')); 
        else
          echo json_encode(array('st'=>1,'msg'=>''));   

      }
      else if($flag==3)  // previous donation date
      {
        $common_settings=$this->blood_general_model->get_common_settings('MINIMUM_GAP_BETWEEN_DONATION');
        $minimum_gap=$common_settings[0]->setting_value1;
        $minimum_gap_days= 30 * $minimum_gap;
        $datetime1 =date_create(date('Y-m-d',strtotime($value)));
        $datetime2 =date_create(date('Y-m-d'));
        $interval = date_diff($datetime1, $datetime2);

        if($interval->days < $minimum_gap_days )
          echo json_encode(array('st'=>0,'msg'=>'<font style="color:red;">Not eligible as per minimum gap criteria</font>')); 
        else
          echo json_encode(array('st'=>1,'msg'=>'')); 
      }
  }
  // Function to validte common settings



  // Function validate
  public function _validate()
  {
    $users_data = $this->session->userdata('auth_users');
    $field_list = mandatory_section_field_list(2);
    //print_r($field_list);die;
    $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
    if(!empty($field_list))
    {
      if($field_list[0]['mandatory_field_id']=='5' && $field_list[0]['mandatory_branch_id']==$users_data['parent_id'])
      {
        $this->form_validation->set_rules('mobile_no', 'mobile no.', 'trim|required|numeric|min_length[10]|max_length[10]');
      }
           
      if($field_list[1]['mandatory_field_id']=='7' && $field_list[1]['mandatory_branch_id']==$users_data['parent_id'])
      { 
        $this->form_validation->set_rules('age_y', 'age', 'trim|required');
      }
    }
    //$this->form_validation->set_rules('donor_email', 'Donor email', 'trim|valid_email|callback_check_email');

     $this->form_validation->set_rules('donor_name', 'Donor name', 'trim|required');

    if ($this->form_validation->run() == FALSE) 
    { 
    
      echo json_encode(array('st'=>0, 'mobile_no'=>form_error('mobile_no'), 'age'=>form_error('age_y'), 'name'=>form_error('donor_name')));
    }
    else
    {
        return "200";
    }
  }
  // Function validate


  public function check_email($str)
  {
    if(!empty($str))
    {
      $this->load->model('blood_bank/general/blood_bank_general_model','blood_general_model');
      $this->load->model('general/general_model','general');
      $post = $this->input->post();
      if(!empty($post['donor_id']) && $post['donor_id']>0)
      {
          return true;
      }
      else
      {
        $userdata = $this->blood_general_model->check_email($str); 
        if(empty($userdata))
        {
          return true;
        }
        else
        { 
          $this->form_validation->set_message('check_email', 'Email already exists.');
            return false;
        }
      }
    }
  }

    public function donor_excel()
    {
       // Starting the PHPExcel library
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          // Field names in the first row
          $fields = array('Donor Code','Donor Name','Relation Name','Mobile NO','Age','Donor Email','Address','Gender','Country','State','City','Blood Group','Mode Of Donation','Previous Donation Date','Registration Date','Height','Weight');
          $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
          $col = 0;
          $row_heading =1;
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
              $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(20);
              
              $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
              $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
               $row_heading++;
               $col++;
          }
          $list = $this->donor->excel_donor_data();
          //print_r($list);
          $rowData = array();
          $data= array();
          if(!empty($list))
          {
            $i=0;
                foreach($list as $donor)
                {
                   $genders = array('0'=>'Female','1'=>'Male','2'=>'Other');
                   $mode_donation = array('1'=>'Camp','2'=>'Center');
                   $mode_of_donation=$mode_donation[$donor->mode_of_donation];
                    $gender = $genders[$donor->gender];
                    $address = $donor->address;
                    $address2 = $donor->address2;
                    $address3 = $donor->address3;
                    $address_add = "";
                    $age_y = $donor->age_y;
                    $age_m = $donor->age_m;
                    $age_d = $donor->age_d;
                    $age = "";
                    if($age_y>0)
                    {
                         $year = 'Years';
                         if($age_y==1)
                         {
                              $year = 'Year';
                         }
                         $age .= $age_y." ".$year;
                    }
                    if($age_m>0)
                    {
                         $month = 'Months';
                         if($age_m==1)
                         {
                              $month = 'Month';
                         }
                         $age .= ", ".$age_m." ".$month;
                    }
                    if($age_d>0)
                    {
                         $day = 'Days';
                         if($age_d==1)
                         {
                              $day = 'Day';
                         }
                         $age .= ", ".$age_d." ".$day;
                    }
                    $address_add.=$address.$address2.$address3;
                    $address_add_total=$address_add;
                                   
                    $patient_age =  $age;
                    if(!empty($donor->previous_donation_date))
                    {
                    $previous_donation_date= date('Y-m-d',strtotime($donor->previous_donation_date));
                  }
                 if(($donor->previous_donation_date=='0000-00-00')||($donor->previous_donation_date=='1970-01-01'))
                  {
                    $previous_donation_date='';
                  }
                   if(!empty($donor->registration_date))
                    {
                    $registration_date= date('Y-m-d',strtotime($donor->registration_date));
                  }
                  if(($donor->registration_date=='0000-00-00')||($donor->registration_date=='1970-01-01'))
                  {
                    $registration_date='';
                  }

                    array_push($rowData,$donor->donor_code,$donor->donor_name,$donor->relation_name,$donor->mobile_no,$patient_age,$donor->donor_email,$address_add_total,$gender,$donor->country,$donor->state,$donor->city,$donor->blood_group,$mode_of_donation,$previous_donation_date,$registration_date,$donor->height,$donor->weight);
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
               foreach($data as $donor_data)
               {
                    $col = 0;
                    $row_val=1;
                    foreach ($fields as $field)
                    { 
                         $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $donor_data[$field]);

                         $objPHPExcel->getActiveSheet()->getStyle($row_val)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                          $objPHPExcel->getActiveSheet()->getStyle($row_val)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                         $col++;
                         $row_val++;
                    }
                    $row++;
               }
               $objPHPExcel->setActiveSheetIndex(0);
               $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
         

          }
        

          header('Content-Type: application/vnd.ms-excel charset=UTF-8');
          header("Content-Disposition: attachment; filename=donor_list_".time().".xls");
          header("Pragma: no-cache"); 
          header("Expires: 0");
         if(!empty($data))
         {
          ob_end_clean();
          $objWriter->save('php://output');
         }
    }


   //Function to view Donor
  public function view_donor($donor_id="")
  { 
    unauthorise_permission('265','1538');
     $this->load->model('blood_bank/donor/donor_examinations_model','donor_examination');
     $data['page_title'] = 'View Donor Details'; 
     $data['donor_data']   = $this->donor->get_by_id($donor_id);
     $examination_data=$this->donor_examination->examination_data('',$donor_id);
    
     $data['examination_data']=$examination_data;
     
     $blood_detail_data=$this->donor_examination->blood_details_data('',$donor_id);
     $data['blood_details']=$blood_detail_data;
     $qc_data=$this->donor_examination->blood_qc_data('',$donor_id);
     $data['qc_data']=$qc_data;
     if($qc_data!="empty")
     { 
      $qc_data_fields=$this->donor_examination->blood_qc_data_fields($qc_data['id'],$donor_id);
      $data['qc_data_fields']=$qc_data_fields;
     } 
     $component_data=$this->donor_examination->blood_components('',$donor_id);
    //print '<pre>'; print_r($component_data);die;
     $data['component_data']=$component_data; 
     
     $this->load->view('blood_bank/donor/view',$data);
  }


  // Function to Delete Donor
  public function delete_donor()
  {
    unauthorise_permission('265','1535');
    $id=$this->input->post('row_id');
    $this->donor->delete_donor($id);
    echo "Donor successfully deleted";
  }
  

  // Function to delete donor all
   public function deleteall()
   {
      unauthorise_permission('265','1535');

      $post = $this->input->post(); 
        //print_r($post);
        //die; 
        if(!empty($post))
        {
            $result = $this->donor->delete_donor_all($post['row_id']);
            $response = "Donor successfully deleted.";
            echo $response;
        }
   } 
  



 
    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission('265','1536');
        $data['page_title'] = 'Donor Archive List';
        $this->load->helper('url');
        $this->load->view('blood_bank/donor/archive',$data);
    }
  



  // Function to get Archive list
   public function archive_ajax_list()
    {
         unauthorise_permission('265','1536');
        $this->load->model('blood_bank/donor/donor_archive_model','donor_archive_model'); 

        $list = $this->donor_archive_model->get_datatables();  
        //print_r($list);die;
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $donor) 
        { 
            $no++;
        $row = array();
        if($donor->status==1)
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
        $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$donor->id.'">'.$check_script; 
        $row[] = $donor->donor_code;  
        $row[] = $donor->donor_name;  
      //  $row[] = $donor->relation." ".$donor->relation_name;  
        $row[] = $donor->mobile_no;
        $row[] = $donor->gender;
        $row[] = hms_patient_age_calulator($donor->age_y,$donor->age_m,$donor->age_d);
       // $row[] = $donor->address;
        $row[] = $donor->blood_group; 
        $row[] = $donor->mode_of_donation; 
        $row[] = ( strtotime($donor->previous_donation_date) > 0 ? date('d-M-Y', strtotime($donor->previous_donation_date)) : ''); 
        $row[] = $donor->preferred_reminder_service;
        $row[] = $donor->height;
        $row[] = ($donor->weight > 0 ? $donor->weight : '') ;
        $row[] = $status;
        $row[] = date('d-M-Y H:i A',strtotime($donor->created_date));
            $users_data = $this->session->userdata('auth_users');
          $btnrestore='';
          $btndelete='';
          if(in_array('1473',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_donor('.$donor->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
          }
          if(in_array('1471',$users_data['permission']['action'])){
               $btndelete = ' <a onClick="return trash('.$donor->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
          }
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->donor_archive_model->count_all(),
                        "recordsFiltered" => $this->donor_archive_model->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

  

  // Function to restore donor
    public function restore($id)
    {
        unauthorise_permission('265','1539');
       $this->load->model('blood_bank/donor/donor_archive_model','donor_archive_model'); 
       if(!empty($id) && $id>0)
       {
           $result = $this->donor_archive_model->restore($id);
           $response = "Donor successfully restore in Donor list.";
           echo $response;
       }
    }

    // Function to restore all donor
    function restoreall()
    { 
       unauthorise_permission('265','1539');
       $this->load->model('blood_bank/donor/donor_archive_model','donor_archive_model'); 
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->donor_archive_model->restoreall($post['row_id']);
            $response = "Donor successfully restore in Donor list.";
            echo $response;
        }
    }

   // Function to trash donor
    public function trash($id="")
    {
        unauthorise_permission('265','1537');
             $this->load->model('blood_bank/donor/donor_archive_model','donor_archive_model'); 
       if(!empty($id) && $id>0)
       {
           $result = $this->donor_archive_model->trash($id);
           $response = "Donor successfully deleted parmanently.";
           echo $response;
       }
    }

    // Function to trash all donor
    function trashall()
    {
         unauthorise_permission('265','1537');
         $this->load->model('blood_bank/donor/donor_archive_model','donor_archive_model'); 
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->donor_archive_model->trashall($post['row_id']);
            $response = "Donor successfully deleted parmanently.";
            echo $response;
        }
    }

    public function advance_search()
    {
        $this->load->model('general/general_model'); 
        $data['page_title'] = "Advance Search";
        $post = $this->input->post();
        
      
        //$data['data']=get_setting_value('PATIENT_REG_NO');
        //print_r($data['data']);die;
       // $data['country_list'] = $this->general_model->country_list();
        $data['simulation_list'] = $this->general_model->simulation_list();
        //$data['religion_list'] = $this->general_model->religion_list();
       // $data['relation_list'] = $this->general_model->relation_list();
        //$data['insurance_type_list'] = $this->general_model->insurance_type_list();
         $this->load->model('blood_bank/general/blood_bank_general_model','blood_general_model');
        $data['blood_groups']=$this->blood_general_model->get_blood_group_list();    
        $data['form_data'] = array(
                                    "simulation_id"=>'',
                                    "start_date"=>"",
                                    "end_date"=>"",
                                    'donor_name'=>'',
                                    'donor_id'=>'',
                                    'blood_group'=>'',
                                    'qc_result'=>'',
                                    'outcome'=>'',                                   
                                  //  'status'=>'1'
                          
                                     );
        if(isset($post) && !empty($post))
        {
            $merge= array_merge($data['form_data'],$post); 
            $this->session->set_userdata('donor_search', $merge);
        }
        $donor_search = $this->session->userdata('donor_search');
        if(isset($donor_search) && !empty($donor_search))
        {
            $data['form_data'] = $donor_search;
        }
        $this->load->view('blood_bank/donor/advance_search',$data);
    }

    public function reset_search()
    {
        $this->session->unset_userdata('donor_search');
    }
    
    //donor certificates
   
  public function print_certificate($ids="")
  {
    $this->load->model('patient/patient_model','patient'); 
    $data['template_data'] = $this->patient->get_template_data_by_branch_id(156,486);
    
    $data['donor_data']   = $this->donor->get_by_id($ids);
    $this->load->view('blood_bank/donor/print_certificate_data',$data);
      
 }
 
 
  public function import_donor_excel()
    {
        //unauthorise_permission(97,628);
        $this->load->library('form_validation');
        $this->load->library('excel');
        $data['page_title'] = 'Import Blood Bank Donor excel';
        $arr_data = array();
        $header = array();
        $path='';

       // print_r($_FILES); die;
        if(isset($_FILES) && !empty($_FILES))
        {
          
            $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
            if(!isset($_FILES['patient_list']) || $_FILES['patient_list']['error']>0)
            {
               
               $this->form_validation->set_rules('patient_list', 'file', 'trim|required');  
            } 
            $this->form_validation->set_rules('name', 'name', 'trim|required');  
            if($this->form_validation->run() == TRUE)
            {
               
                $config['upload_path']   = DIR_UPLOAD_PATH.'temp/'; 
                $config['allowed_types'] = '*'; 
                $this->load->library('upload', $config);
                if(!$this->upload->do_upload('patient_list')) 
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
                    $total_patient = count($arrs_data);
                    
                   $array_keys = array('unit_no','reg_no','donor_name','gender','dob','age_y','mobile_no','address','marital_status','blood_group_id','mode_of_donation','height','weight','reg_date');
                
               
                    $count_array_keys = count($array_keys);
                    $array_values_keys = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N');
                    $patient_data = array();
                    
                    $j=0;
                    $m=0;
                    $data='';
                    $patient_all_data= array();
                    for($i=0;$i<$total_patient;$i++)
                    {
                        $med_data_count_values[$i] = count($arrs_data[$i]);
                        for($p=0;$p<$count_array_keys;$p++)
                        {
                            if(array_key_exists($array_values_keys[$p],$arrs_data[$i]))
                            {
                                $patient_all_data[$i][$array_keys[$p]] = $arrs_data[$i][$array_values_keys[$p]];
                            }
                            else
                            {
                                $patient_all_data[$i][$array_keys[$p]] ="";
                            }
                        }
                    }
                    //echo "<pre>"; print_r($patient_all_data); exit;
                    $this->donor->save_all_blood_donor($patient_all_data);
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
                //echo "<pre>"; print_r($data['form_error']); exit;
                

            }
        }

        $this->load->view('blood_bank/donor/import_donor_excel',$data);
    } 


    public function sample_donor_import_excel()
    {
            //unauthorise_permission(97,627);
            // Starting the PHPExcel library
            $this->load->library('excel');
            $this->excel->IO_factory();
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
            $objPHPExcel->setActiveSheetIndex(0);
            // Field names in the first row
            $fields = array('unit_no','Registration','Donor Name(*)','Gender(M OR F)','DOB(dd-mm-yyyy)','Age','Phone number','Address','Marital Status(Married-1/Unmarried-0)','Blood Group(*)(Eg. A+Ve,B+Ve..)','Mode of Donation[Camp/Center]','Height','Weight','Reg. Date(*)(dd-mm-yyyy)');
      
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
            header("Content-Disposition: attachment; filename=donor_excel1_".time().".xls");  
            header("Pragma: no-cache"); 
            header("Expires: 0");
            ob_end_clean();
            $objWriter->save('php://output');
           
    }
   

// Please write code above this
}
?>
