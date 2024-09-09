<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Pediatrician_prescription extends CI_Controller 
{
 
  function __construct() 
  {
    parent::__construct();  
    auth_users();  
      $this->load->model('pediatrician/pediatrician_prescription/Pediatrician_prescription_model','prescription_model');
      $this->load->model('pediatrician/pediatrician_age_chart/Pediatrician_age_chart_model','age_chart');
    $this->load->model('ipd_booking/ipd_booking_model','ipd_booking');
    $this->load->library('form_validation');
  }

  public function index()
  {
    unauthorise_permission(275,1629);
    $this->session->unset_userdata('billing_vaccine_ids');
  }
  

  // Function to open add form starts here
  public function add_growth_prescription($booking_id="",$patient_id="",$growth_id="")
  {
    unauthorise_permission(275,1629);
  
    if($booking_id!='')
    {
      $data['get_growth_booking_id']=$this->prescription_model->get_growth_pres_by_id($booking_id,$patient_id,$growth_id);
      $data['growth_prescription_ipd_data'] = $this->prescription_model->get_by_opd_id($booking_id);
      
     
      $patient_id=$data['growth_prescription_ipd_data']['patient_id'];

    }
    if(($booking_id!="") && ($data['get_growth_booking_id']['id']!=''))
    {
      $data['growth_prescription_data'] = $this->prescription_model->get_by_id($booking_id,$growth_id);
      $data['growth_prescription_data']['age_y']=$data['growth_prescription_ipd_data']['age_y'];
      $data['growth_prescription_data']['age_m']=$data['growth_prescription_ipd_data']['age_m'];
      $data['growth_prescription_data']['age_d']=$data['growth_prescription_ipd_data']['age_d'];

      $data['growth_id']     = $data['growth_prescription_data']['id'];
      $data['booking_id']     = $data['growth_prescription_data']['booking_id'];
      $data['patient_id']     = $data['growth_prescription_data']['patient_id'];
      $data['growth_prescription_data']['dob']=$data['growth_prescription_ipd_data']['dob'];
      $data['page_title']=     "Edit Pediatrician Growth Prescription";
    }  
    else
    {

      $data['growth_prescription_data']   = "empty";
      if($data['growth_prescription_ipd_data']['age_y']!=0)
      {
        $data['growth_prescription_ipd_data']['age_y']=$data['growth_prescription_ipd_data']['age_y'];
      }
      else
      {
        $data['growth_prescription_ipd_data']['age_y']='';
      }
      if($data['growth_prescription_ipd_data']['age_m']!=0)
      {
        $data['growth_prescription_ipd_data']['age_m']=$data['growth_prescription_ipd_data']['age_m'];
      }
      else
      {
        $data['growth_prescription_ipd_data']['age_m']='';
      }
      if($data['growth_prescription_ipd_data']['age_d']!=0)
      {
        $data['growth_prescription_ipd_data']['age_d']=$data['growth_prescription_ipd_data']['age_d'];
      }
      else
      {
        $data['growth_prescription_ipd_data']['age_d']='';
      }
      if($data['growth_prescription_ipd_data']['dob']!=0)
      {
        $data['growth_prescription_ipd_data']['dob']=$data['growth_prescription_ipd_data']['dob'];
      }
      else
      {
        $data['growth_prescription_ipd_data']['dob']='';
      }
      
      // $data['growth_prescription_data']['age_m']=$data['growth_prescription_ipd_data']['age_m'];
      // $data['growth_prescription_data']['age_d']=$data['growth_prescription_ipd_data']['age_d'];
      // $data['growth_prescription_data']['dob']=$data['growth_prescription_ipd_data']['dob'];

      $data['growth_id']     = "0";
      $data['booking_id']     = $booking_id;
      $data['patient_id']     = $patient_id;
      $data['page_title']="Add Pediatrician Growth Prescription";
    }
    $users_data = $this->session->userdata('auth_users');
    $branch_id=$users_data['parent_id'];
    
    $this->load->view('pediatrician/pediatrician_prescription/add_growth',$data);
  }


  // function to open add form ends here     
 public function save_growth_prescription()
  {
    $response = $this->_validate();
    $post=$this->input->post();
    //print_r($post);
    if($response!=200)
    {
      echo $response;
    }
    else
    {
      $users_data = $this->session->userdata('auth_users');
      $branch_id=$users_data['parent_id'];
      $growth_id=$this->input->post('growth_id');

      $booking_id=$this->input->post('booking_id');
       $patient_id=$this->input->post('patient_id');
      $date_to_visit='';
      $dob='';
      if(!empty($this->input->post('date_to_visit')))
      {
       $date_to_visit = date('Y-m-d', strtotime($this->input->post('date_to_visit')));  
      }
      else
      {
        $date_to_visit='';
      }
      if(!empty($this->input->post('dob')))
      {
       $dob = date('Y-m-d', strtotime($this->input->post('dob')));  
      }
      else
      {
        $dob='';
      }
      if(!empty($this->input->post('oedema')))
      {
        $oedema_weight='';
        $weight='';
        $oedema_weight=$this->input->post('oedema');
        $weight=$this->input->post('weight');
        if($oedema_weight==2)
        {
          $weight=$weight;

        }
        else{
           $weight='';
        }
      }
    
    $triceps_skinfold = $this->input->post('triceps_skinfold');
    if(!empty(triceps_skinfold))
    {
       $triceps_skinfold =  $triceps_skinfold;
    }
    else
    {
        $triceps_skinfold='0.00';
    }
    $subscapular_skinfold = $this->input->post('subscapular_skinfold');
    if(!empty(triceps_skinfold))
    {
       $subscapular_skinfold =  $subscapular_skinfold;
    }
    else
    {
        $subscapular_skinfold='0.00';
    }
    $head_circumference = $this->input->post('head_circumference');
    if(!empty(triceps_skinfold))
    {
       $head_circumference =  $head_circumference;
    }
    else
    {
        $head_circumference='0.00';
    }
    
    $muac = $this->input->post('muac');
    if(!empty(triceps_skinfold))
    {
       $muac =  $muac;
    }
    else
    {
        $muac='0.00';
    }
    
      $growth_data_array=array(
                           //'id'=>$this->input->post('growth_id'),
                           'booking_id'=>$this->input->post('booking_id'),
                           'patient_id'=>$this->input->post('patient_id'),
                           'branch_id'=>$branch_id,
                           'sex'=>$this->input->post('sex'),
                           'age_y'=>$this->input->post('age_y'),
                           'age_m'=>$this->input->post('age_m'),
                           'age_d'=>$this->input->post('age_d'),
                           'notes'=>$this->input->post('notes'),
                           'weight'=>$weight,
                           'height'=>$this->input->post('height'),
                           'measured'=>$this->input->post('measured'),
                           'oedema'=>$this->input->post('oedema'),
                           'head_circumference'=>$head_circumference,
                           'muac'=>$muac,
                           'dob'=>$dob,
                           'date_to_visit'=>$date_to_visit,
                           'triceps_skinfold'=>$triceps_skinfold,
                           'subscapular_skinfold'=>$subscapular_skinfold,
                           'status'=>'1',
                           'ip_address'=>$_SERVER['REMOTE_ADDR'],
                           );
      //print_r($growth_data_array);
      //die;
      
      /* update patient table */
          $data_patient = array(
          'age_y'=>$post['age_y'],
          'age_m'=>$post['age_m'],
          'age_m'=>$post['age_d'],
          'dob'=>date('Y-m-d',strtotime($post['dob']))
          );
          $patient_condition=" id=".$patient_id."";

          $data_patient["modified_by"]=$users_data['id'];
          $data_patient["modified_date"]=date('Y-m-d H:i:s');
          $this->prescription_model->common_update('hms_patient',$data_patient, $patient_condition);
      /* update patient table */

      if($growth_id > 0)
      {
       
       
        $growth_condition=" id=".$growth_id." and booking_id=".$booking_id." and branch_id=".$branch_id." ";
        $growth_data_array["modified_by"]=$users_data['id'];
        $growth_data_array["modified_date"]=date('Y-m-d H:i:s');
        $growth_rec_id=$this->prescription_model->common_update('hms_pediatrician_growth_prescription',$growth_data_array, $growth_condition);
        echo json_encode(array('st'=>1, 'msg'=>'Growth updated successfully'));

      }
      else
      {

        $growth_data_array["branch_id"]=$branch_id;
        $growth_data_array["created_by"]=$users_data['id'];
        $growth_data_array["created_date"]=date('Y-m-d H:i:s');
        $growth_rec_id = $this->prescription_model->common_insert('hms_pediatrician_growth_prescription',$growth_data_array);
        //echo $this->db->last_query(); exit;
        if(!empty($growth_rec_id))
        {
        echo json_encode(array('st'=>1, 'msg'=>'Growth inserted successfully'));
        }
      }
    }
  }

  
  // Function validate
  public function _validate()
  {
    $users_data = $this->session->userdata('auth_users');
    //$field_list = mandatory_section_field_list(2);
    $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
    $this->form_validation->set_rules('date_to_visit', 'date of visit', 'trim|required');
    $this->form_validation->set_rules('dob', 'dob', 'trim|required');
   // $this->form_validation->set_rules('weight', 'weight', 'trim|required');
    $this->form_validation->set_rules('height', 'height', 'trim|required');
    $this->form_validation->set_rules('measured', 'measured', 'trim|required');
    $this->form_validation->set_rules('oedema', 'oedema', 'trim|required');
    $this->form_validation->set_rules('age_y', 'age', 'trim|required');


    if ($this->form_validation->run() == FALSE) 
    { 
       
      echo json_encode(array('st'=>0, 'age_error'=>form_error('age_y'), 'date_to_visit_error'=>form_error('date_to_visit'), 'dob_error'=>form_error('dob'), 'height_error'=>form_error('height'), 'measured_error'=>form_error('measured'), 'oedema_error'=>form_error('oedema')));
    }
    else
    {
        return "200";
    }
  }

  public function ajax_list($patient_id='')
    { 
    	//print_r($booking_id);

        unauthorise_permission(275,1629);
        $list = $this->prescription_model->get_datatables($patient_id);  
       // print '<pre>'; print_r($list);die;
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        $prescription_ids=array();
       
        foreach ($list as $prescription_list) {

        // print_r($prescription_list);die;
            $no++;
            $row = array();
            if($prescription_list->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else
            {
                $status = '<font color="red">Inactive</font>';
            } 
            
            ////////// Check  List /////////////////medicine_preferred_reminder_service
            $check_script = "";
            if($i==$total_num)
            {
            } 
            $measured='';
            $oedema='';
            $sex='';
            if(!empty($prescription_list->measured))
            {
              if($prescription_list->measured==1)
              {
                $measured='Recumbent';

              }
              else
              {
                $measured='Standing';
              }
            }
            //print_r($prescription_list->oedema);
             if(!empty($prescription_list->oedema))
            {
              if($prescription_list->oedema==1)
              {
                $oedema='Yes';

              }
              else
              {
                $oedema='No';
              }
            }

             if(!empty($prescription_list->sex))
            {
              if($prescription_list->sex==0)
              {
                $sex='Male';

              }
              if($prescription_list->sex==1)
              {
                $sex='Female';

              }

              if($prescription_list->sex==2)
              {
                $sex='Others';

              }
            }
            ////////// Check list end ///////////// 
              $row[] = '<input type="checkbox" name="prescription_ids[]" class="checklist" value="'.$prescription_list->id.'">'.$check_script; 
               $row[]=$prescription_list->opd_no; 
              $row[] = ( strtotime($prescription_list->date_to_visit) > 0 ? date('d-M-Y', strtotime($prescription_list->date_to_visit)) : ''); 
              $row[] = $prescription_list->patient_name;
              $row[]=$prescription_list->weight; 
              $row[]=$oedema;
              $row[]=$measured;
              $row[]=$prescription_list->height;
              $row[]=$prescription_list->muac;
              $row[]=$prescription_list->head_circumference;
              $row[]=$prescription_list->triceps_skinfold;
              $row[]=$prescription_list->subscapular_skinfold;
            
            $row[]=$sex;
            $row[] = ( strtotime($prescription_list->dob) > 0 ? date('d-M-Y', strtotime($prescription_list->dob)) : ''); 
            $row[] = hms_patient_age_calulator($prescription_list->age_y,$prescription_list->age_m,$prescription_list->age_d);
            $row[] = $status;
            $row[] = date('d-M-Y H:i A',strtotime($prescription_list->created_date)); 
            $users_data = $this->session->userdata('auth_users');
            //$btnedit='';
            $btndelete='';
            $actions='';
           
         //if(in_array('1509',$users_data['permission']['action']))
            //{
            
            $growth_print = ' <a class="btn-custom" onClick="return print_growth_type('.$prescription_list->id.')" href="javascript:void(0)" title="Print" data-url="512"><i class="fa fa-pencil"></i> Print </a> '; 
            $edit_delete = ' <a class="btn-custom" onClick="return edit_growth_type('.$prescription_list->id.')" href="javascript:void(0)" title="Edit" data-url="512"><i class="fa fa-pencil"></i> Edit</a> '; 
            $btndelete = ' <a class="btn-custom" onClick="return delete_growth_type('.$prescription_list->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
            //}
        $row[] = $growth_print.$btndelete.$edit_delete;
            $data[] = $row;
            $i++;
     }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->prescription_model->count_all(),
                        "recordsFiltered" => $this->prescription_model->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function delete($id="")
    {
      unauthorise_permission(275,1624);
     
       if(!empty($id) && $id>0)
       {
           $result = $this->prescription_model->delete($id);
           $response = "Growth successfully deleted.";
           echo $response;
       }
    }

    public function deleteall()
    {
       unauthorise_permission(275,1624);
        $post = $this->input->post(); 
        if(!empty($post))
        {
            $result = $this->prescription_model->deleteall($post['row_id']);
            $response = "Growth successfully deleted.";
            echo $response;
        }
    }
    


  public function add_vaccination_prescription($booking_id="",$date_se="",$patient_id="")
  {
      $this->session->unset_userdata('billing_vaccine_ids');
      $date_select=date('d-m-Y',strtotime($date_se));
      //print_r($date_select);
      $this->load->model('opd/opd_model','opd'); 
      $opd_booking_data = $this->opd->get_by_id($booking_id);
      //$this->prescription_model->get_by_id_peditration_vaccine($edit_id);
      $data['vaccination_list']= $this->age_chart->vaccination_list();
      $data['age_list']= $this->age_chart->age_list();
      $data['date_age_list']= $this->prescription_model->date_age_list($date_select);
       $data['print_type']= '';
      $data['list'] = $this->age_chart->get_datatables(); 
      $data['booking_id']=$booking_id;
      $data['patient_id']=$patient_id;
      $data['vaccine_date']=$date_select;
      $data['page_title'] = 'Vaccination Prescription'; 
      $data['opd_booking_data']=$opd_booking_data;
      
      
      
      $data['attended_doctor'] = $opd_booking_data['attended_doctor'];
      $this->load->view('pediatrician/pediatrician_prescription/vaccination_pediatrician',$data);
  }
  public function print_vaccination_details($booking_id="",$date_se="",$patient_id="" ,$type="")
  {    
      $data['print_status']="1";
      $date_select=date('d-m-Y',strtotime($date_se));
      $this->load->model('opd/opd_model','opd'); 
      $opd_booking_data = $this->opd->get_by_id($booking_id);
      $data['vaccination_list']= $this->age_chart->vaccination_list();
      $data['age_list']= $this->age_chart->age_list();
      $data['date_age_list']= $this->prescription_model->date_age_list($date_select);
      $data['print_type']= $type;
      $data['list'] = $this->age_chart->get_datatables(); 
      $data['booking_id']=$booking_id;
      $data['patient_id']=$patient_id;
      $data['vaccine_date']=$date_se;
      $data['page_title'] = 'Vaccination Prescription'; 
      $data['opd_booking_data']=$opd_booking_data;
      $this->load->view('pediatrician/pediatrician_prescription/vaccination_pediatrician',$data); 
  }

  public function pdf_vaccination_details($booking_id="",$date_se="",$patient_id="",$type="",$mail_send="")
  {    
    
      $data['print_status']="1";
      $date_select=date('d-m-Y',strtotime($date_se));
      $this->load->model('opd/opd_model','opd'); 
      $opd_booking_data = $this->opd->get_by_id($booking_id);
      $data['vaccination_list']= $this->age_chart->vaccination_list();
      $data['age_list']= $this->age_chart->age_list();
      $data['date_age_list']= $this->prescription_model->date_age_list($date_select);
      $data['list'] = $this->age_chart->get_datatables(); 
      $data['booking_id']=$booking_id;
      $data['patient_id']=$patient_id;
      $data['vaccine_date']=$date_se;
      $data['print_type']= $type;
      $data['page_title'] = 'Vaccination Prescription'; 
      $data['opd_booking_data']=$opd_booking_data;
      $this->load->library('m_pdf');
      $view_detail= $this->load->view('pediatrician/pediatrician_prescription/vaccination_pediatrician',$data,true); 
      $stylesheet = file_get_contents(ROOT_CSS_PATH.'vaccine_pres_pdf.css'); 
      $this->m_pdf->pdf->WriteHTML($stylesheet,1); 
      //echo $view_detail;die;
      

        $file_name = "vaccination_report_".$booking_id.".pdf";
        if($mail_send=="mail_send")
        {
         

          $pdfFilePath = DIR_UPLOAD_PATH.'temp/'.$file_name; 
          $this->m_pdf->pdf->WriteHTML($view_detail,2);
          $this->m_pdf->pdf->Output($pdfFilePath, "F");
        }
        else
        {
          $this->m_pdf->pdf->WriteHTML($view_detail,2);
          $this->m_pdf->pdf->Output($file_name, "I");
        }
  }

  public function print_all_vaccination_details($booking_id="",$date_se="",$patient_id="",$type="",$mail_send="")
  {


  }
  public function save_date_vaccine($vaccine_id="",$age_id="",$booking_id="",$patient_id="",$date_vaccine="",$get_span='',$edit_id='')
   {
     
    $this->load->model('general/general_model'); 
    $data['page_title']="Vaccination Billing";
    $data['payment_mode']=$this->general_model->payment_mode();
    $data['res_vaccine'] = $this->prescription_model->check_stock_avability($vaccine_id);
    
    if(isset($data['res_vaccine']->avl_qty) && $data['res_vaccine']->avl_qty>0)
    {
     $avl_qty=$data['res_vaccine']->avl_qty;
    }
    else
    {
      $avl_qty='No Stock Avaliable';
    }
    if(isset($data['res_vaccine']->mrp))
    {
      $mrp= $data['res_vaccine']->mrp;
    }
    else
    {
      $mrp='';
    }
    if(isset($data['res_vaccine']->discount))
    {
      $discount= $data['res_vaccine']->discount;
    }
    else
    {
      $discount='';
    }
    if(isset($vaccine_id))
    {
      $vaccine_id= $vaccine_id;
    }
    else
    {
      $vaccine_id='';
    }
    if(isset($age_id))
    {
      $age_id= $age_id;
    }
    else
    {
      $age_id='';
    }
    if(isset($edit_id))
    {
      $edit_id= $edit_id;
    }
    else
    {
      $edit_id='';
    }
    if(!empty($edit_id))
    {
      $result = $this->prescription_model->get_by_id_peditration_vaccine($edit_id);
      $data['payment_mode']=$this->general_model->payment_mode();
      $get_payment_detail= $this->prescription_model->payment_mode_detail_according_to_field($result['payment_mode'],$edit_id);
      $total_values='';
      for($i=0;$i<count($get_payment_detail);$i++) 
      {
      $total_values[]= $get_payment_detail[$i]->field_value.'_'.$get_payment_detail[$i]->field_name.'_'.$get_payment_detail[$i]->field_id;
      }
       
       $data['form_data']=array(
                            'data_id'=>$edit_id, 
                            //'vaccination_date_time'=>date('d-m-Y',strtotime($result['vaccination_date_time'])),
                            'qty'=>$avl_qty,
                            'vaccine_id'=>$result['vaccine_id'],
                            'age_id'=>$result['age_id'],
                             "field_name"=>$total_values,
                            'booking_id'=>$result['booking_id'],
                            'patient_id'=>$result['patient_id'],
                            'attended_doctor'=>$result['attended_doctor'],
                            'total_amount'=>$result['total_amount'],
                            'discount'=>$result['discount'],
                            'get_span'=>$get_span,
                            'net_amount'=>$result['net_amount'],
                            'payment_mode'=>$result['payment_mode'],
                            'paid_amount'=>$result['paid_amount'],
                            'balance'=>$result['balance']
                            );
     
    }
    else
    {
      $data['form_data']=array(
                            'data_id'=>$edit_id,
                            'qty'=>$avl_qty,
                            'total_amount'=>$mrp,
                            //'vaccination_date_time'=>"",
                            'vaccine_id'=>$vaccine_id,
                            'age_id'=>$age_id,
                             "field_name"=>'',
                            'payment_mode'=>'',
                            'attended_doctor'=>'',
                            'booking_id'=>$booking_id,
                            'get_span'=>$get_span,
                            'patient_id'=>$patient_id,
                            'discount'=>$discount 
                            );
    }
    
   // print_r($data['form_data']);die;
    $data['attended_doctor'] = $this->ipd_booking->attended_doctor_list();

    $this->load->view('pediatrician/pediatrician_prescription/billing_vaccine',$data);
   }


  public function add_vaccine_pres()
   {
        unauthorise_permission(275,1630);
        $this->session->unset_userdata('book_vaccine_id');
        $data['page_title'] = "Vaccination Billing";
        $data['last_id']='';   
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'vaccination_date_time'=>"",
                                  'attended_doctor'=>"",
                                  'total_amount'=>"",
                                  'discount_percent'=>"",
                                  'discount_amount'=>"",
                                  'cgst_amount'=>'',
                                  'igst_amount'=>'',
                                  'sgst_amount'=>'',
                                  'net_amount'=>"",
                                  'paid_amount'=>"",
                                  "field_name"=>'',
                                  'balance'=>"" ,
                                 
                                  );    
      //$data['last_id']=''; 
        if(isset($post) && !empty($post))
        {
          
            //$data['form_data'] = $this->_validate_vaccine();
            //  if($this->form_validation->run() == TRUE)
            // {
               $last_id=$this->prescription_model->save_vaccine_pres();
               $this->session->set_userdata('book_vaccine_id', $last_id);
            
                echo 1;
               return false;
                
           //   }
           //  else
           //  {
           //  $data['form_error'] = validation_errors();  
           // }     
        }
        $data['attended_doctor'] = $this->ipd_booking->attended_doctor_list();
        $this->load->model('general/general_model'); 
        $data['payment_mode']=$this->general_model->payment_mode();
        $this->load->view('pediatrician/pediatrician_prescription/billing_vaccine',$data); 
     }


   public function edit_vaccine_pres($id="")
   {
      
      unauthorise_permission(275,1630);
        $result = $this->prescription_model->get_by_id_prescription($id); 
        //print_r($result);
        //die;
          if(!empty($result))
          {
            if(!empty($result['vaccination_date_time']))
            {
              if(($result['vaccination_date_time']=='0000-00-00 00:00:00')&&($result['vaccination_date_time']=='01-01-1970 05:30:00'))
              {
                $vaccination_date_time='';
              }
              else
              {
                $vaccination_date_time=date('d-m-Y H:i:s',strtotime($result['vaccination_date_time']));
              }
            }
          }
        $data['page_title'] = "Vaccination Billing";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>$id, 
                                  'vaccination_date_time'=>$vaccination_date_time,
                                  'attended_doctor'=>$result['attended_doctor'],
                                  'total_amount'=>$result['total_amount'],
                                  'discount_amount'=>$result['discount_amount'],
                                  'cgst_amount'=>$result['cgst'],
                                  'igst_amount'=>$result['igst'],
                                  'sgst_amount'=>$result['sgst'],
                                  'net_amount'=>$result['net_amount'],
                                  'paid_amount'=>$result['paid_amount'],
                                  'balance'=>$result['balance']
                                  );    

        if(isset($post) && !empty($post))
        {   
          //$data['form_data'] = $this->_validate_vaccine();
          //if($this->form_validation->run() == TRUE)
          //{
          $last_id=$this->prescription_model->save_vaccine_pres();
           $this->session->set_userdata('book_vaccine_id', $last_id);
          echo 1;
          return false;

          //}
          //else
          //{
          //$data['form_error'] = validation_errors();  
          //}     
        }
      $data['attended_doctor'] = $this->ipd_booking->attended_doctor_list();
      $this->load->model('general/general_model'); 
      $data['payment_mode']=$this->general_model->payment_mode();
       $this->load->view('pediatrician/pediatrician_prescription/billing_vaccine',$data); 
       
   
   }   

   private function _validate_vaccine()
    {
        $post = $this->input->post();    
        $total_values=array();
        $this->load->model('general/general_model'); 
        $data['attended_doctor'] = $this->ipd_booking->attended_doctor_list();
        if(isset($post['field_name']))
        {
            $count_field_names= count($post['field_name']);  
            $get_payment_detail= $this->general_model->payment_mode_detail($post['payment_mode']);
            for($i=0;$i<$count_field_names;$i++) 
            {
              $total_values[]= $post['field_name'][$i].'_'.$get_payment_detail[$i]->field_name.'_'.$get_payment_detail[$i]->id;

            }
        }
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('vaccination_date_time', 'vaccination date time', 'trim|required'); 
         if(isset($post['field_name']))
        {
          $this->form_validation->set_rules('field_name[]', 'field', 'trim|required'); 
        }
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(2); 
            $data['form_data']=array(
                            //'vaccination_date_time'=>date('d-m-Y H:i:s',strtotime($post['vaccination_date_time'])),
                            'vaccine_id'=>$post['vaccine_id'],
                            'age_id'=>$post['age_id'],
                             "field_name"=>$total_values,
                            'booking_id'=>$post['booking_id'],
                            'patient_id'=>$post['patient_id'],
                            //'attended_doctor'=>$post['attended_doctor'],
                            'total_amount'=>$post['total_amount'],
                            'discount'=>$post['discount'],
                            'net_amount'=>$post['net_amount'],
                            'payment_mode'=>$post['payment_mode'],
                            'paid_amount'=>$post['paid_amount'],
                            'balance'=>$post['balance']
                             );
            return $data['form_data'];
        }   
    }

  // public function payment_calc_all()
  //   { 
  //     $post = $this->input->post();
  //     $total_amount = 0;
  //     $total_discount =0;
  //     $net_amount =0;  
  //     $total_discount =0;
  //     $net_amount =0;  
  //     $total_new_amount=0;
  //     $tot_discount_amount=0;
  //     $payamount=0;

  //     if($post['discount']!='' && $post['discount']!=0)
  //     {
  //       $total_discount = ($post['discount']/100)*$post['total_amount'];}
  //     else
  //     {
  //       $total_discount=0;
  //     }
  //     $net_amount = ($post['total_amount']-$total_discount);
  //     if($post['pay']==1 || $post['data_id']!='')
  //     {
  //     $payamount=$post['pay_amount'];
  //     }else
  //     {
  //     $payamount=$net_amount;
  //     }
  //     $blance_dues=$net_amount -$payamount;
  //     $blance_due = number_format($blance_dues,2,'.','');
  //     $payamount = number_format($payamount,2,'.','');
  //     $pay_arr = array('total_amount'=>number_format($post['total_amount'],2,'.',''), 'net_amount'=>number_format($net_amount,2,'.',''),'pay_amount'=>$payamount,'balance_due'=>$blance_due,'discount'=>$post['discount'],'discount_amount'=>number_format($total_discount,2,'.',''));
  //     $json = json_encode($pay_arr,true);
  //     echo $json;die;
  //   }

    public function get_payment_mode_data()
    {
        $this->load->model('general/general_model'); 
        $payment_mode_id= $this->input->post('payment_mode_id');
        $error_field= $this->input->post('error_field');

        $get_payment_detail= $this->general_model->payment_mode_detail($payment_mode_id); 
        $html='';
        $var_form_error='';
        //print_r($get_payment_detail);die;
        foreach($get_payment_detail as $payment_detail)
        {

        if(!empty($error_field))
        {

        $var_form_error= $error_field; 
        }
        $html.='<div class="sale_medicine_mod_of_payment"><label>'.$payment_detail->field_name.'<span class="star">*</span></label><input type="text" name="field_name[]" value="" onkeypress="payment_calc_all();"><input type="hidden" value="'.$payment_detail->id.'" name="field_id[]" /><div class="f_right">'.$var_form_error.'</div></div>';
        }
        echo $html;exit;

    }

    


  public function print_pediatrician_prescription_recipt($ids="")
  {
      $user_detail= $this->session->userdata('auth_users');
      $this->load->model('general/general_model');

      if(!empty($ids))
      {
        $prescription_id= $ids;
      }
      else
      {
        $prescription_id= $this->session->userdata('book_vaccine_id');
      }

      $data['page_title'] = "Add";
      $get_detail_by_id= $this->prescription_model->get_by_id_prescription($prescription_id);

      $get_detail_by_detail= $this->prescription_model->get_by_id_print_prescription($prescription_id,$get_detail_by_id['branch_id']);
   
      $get_payment_detail=$this->prescription_model->get_prescription_payment_details_print($prescription_id);
      
        $get_payment_detail_by_id= $this->general_model->payment_mode_by_id('',$get_payment_detail['payment_mode']);
      
      $template_format= $this->prescription_model->template_format(array('section_id'=>11,'types'=>1,'sub_section'=>0,'branch_id'=>$get_detail_by_id['branch_id']));

      $data['template_data']=$template_format;
      $data['user_detail']=$user_detail;
      $data['patient_detail']= $get_detail_by_id;
      $data['all_detail']= $get_detail_by_detail;

     // print '<pre>'; print_r($data['all_detail']);die;

      $data['payment_mode_by_id']= $get_payment_detail_by_id;
      $data['payment_mode']= $get_payment_detail;
      $this->load->view('pediatrician/pediatrician_vaccine_presciption_print_setting/print_template_prescription',$data);

  }

  public function history_vaccine_pres($booking_id="")
    {
       $data['page_title']="Vaccination History";
       $data['vaccine_history']= $this->prescription_model->get_vaccine_history($booking_id);
       $this->load->view('pediatrician/pediatrician_prescription/history_vaccine_pres',$data); 
    }


    function checkuser_vaccine()
    {
       $data['vaccine_patient_list']= $this->prescription_model->getopdPatient();
      
       $next_date_of_vaccine = date('Y-m-d', strtotime("+1 day"));
       $dob_b=array();
       $dob_s=array();
       $i=0;
       $date_new=date('Y-m-d');
       $age_ids_arr=array();
       foreach($data['vaccine_patient_list'] as $vaccine_patient)
       {

        if(strtotime($vaccine_patient->dob)>86400)
          {

            $age_vaccin_list = $this->prescription_model->age_vaccin_list($vaccine_patient->branch_id);

            if(!empty($age_vaccin_list))
            {
               foreach($age_vaccin_list as $age_vaccin)
               {  
                     $vaccine_related_age= $this->prescription_model->vaccine_related_age_data($age_vaccin->id); 

                     foreach($vaccine_related_age as $vaccine_age_type)
                      { 
                        if($vaccine_age_type->type==1)
                         {
                          $age_ids_arr[]=$vaccine_age_type->age_id;
                         }
                      }


                   

               }



            } 
          }

          
          $i++;
       }

       /* age new loop start here */
       foreach($data['vaccine_patient_list'] as $vaccine_patient)
       {
        $ages_new= array_unique($age_ids_arr);
       foreach ($ages_new as $age_arr)
       {
        
          $age_list= $this->prescription_model->vacc_age_list($age_arr);
                       

                             if(!empty($age_list)) // age data start here//
                             {
                                
                                foreach($age_list as $ages)
                                {
                                 
                                   if($ages->start_age_type==1)
                                    {  //for day //

                                      $date1=date_create($vaccine_patient->dob);
                                      $date2=date_create($next_date_of_vaccine);
                                      $diff=date_diff($date1,$date2);
                                      $current_patient_dob = $diff->format("%a");
                                          /* for start_age */
                                      $start_age_date_latest1= date_create(date('Y-m-d', strtotime($date_new. ' - '.$ages->start_age.' days')));
                                      $start_age_date_latest2 = date_create(date('Y-m-d'));
                                      $diff_altedt=date_diff($start_age_date_latest1,$start_age_date_latest2);
                                      $current_patient_dob_diffstart = $diff_altedt->format("%a");

                                     /* for start_age */


                                       /* for end age */
                                    $start_age_date_latest1= date_create(date('Y-m-d', strtotime($date_new. ' - '.$ages->end_age.' days')));
                                    $start_age_date_latest2 = date_create(date('Y-m-d'));
                                    $diff_altedt=date_diff($start_age_date_latest1,$start_age_date_latest2);
                                    $current_patient_dob_diffend = $diff_altedt->format("%a");

                                     /* for end_age */
                                   if($current_patient_dob>=$current_patient_dob_diffstart && $current_patient_dob<=$current_patient_dob_diffend)
                                      {
                                        //echo "<pre>"; print_r($age_vaccin);
                                        //echo "<pre>";print_r($vaccine_patient);

                                          $mobile_no=$vaccine_patient->mobile_no;
                                          $patient_name= $vaccine_patient->patient_name;
                                          $patient_email= $vaccine_patient->patient_email;

                                          $vaccination_date=date('d-m-Y',strtotime($next_date_of_vaccine));

                                          if(!empty($vaccine_patient->mobile_no))
                                          {

                                             send_sms('pedic_vaccine_pres',27,$vaccine_patient->patient_name,$vaccine_patient->mobile_no,array('{Patientname}'=>$vaccine_patient->patient_name,'{vaccination_date}'=>$vaccination_date));
                                          }
                                          if(!empty($vaccine_patient->patient_email))
                                          {

                                            $this->load->library('general_functions');
                                            $this->general_functions->email($vaccine_patient->patient_email,'','','','','1','pedic_vaccine_pres','27',array('{patient_name}'=>$vaccine_patient->patient_name,'{vaccination Date}'=>$vaccination_date));

                                          }

                                      
                                      }
                                       // for days only
                                    
                                    }
                                    if($ages->start_age_type==2)
                                    {  //for week //
                                       
                                      $date1=date_create($vaccine_patient->dob);
                                      $date2=date_create($next_date_of_vaccine);
                                      $current_patient_dob = $diff->format("%a");

                                      /* for start_age */
                                    $start_age_date_latest1= date_create(date('Y-m-d', strtotime($date_new. ' - '.$ages->start_age.' week')));
                                    $start_age_date_latest2 = date_create(date('Y-m-d'));

                                    $diff_altedt=date_diff($start_age_date_latest1,$start_age_date_latest2);
                                    $current_patient_dob_diffstart = $diff_altedt->format("%a");
                                    

                                     /* for start_age */


                                       /* for end age */
                                    $start_age_date_latest1= date_create(date('Y-m-d', strtotime($date_new. ' - '.$ages->end_age.' week')));
                                    $start_age_date_latest2 = date_create(date('Y-m-d'));
                                    $diff_altedt=date_diff($start_age_date_latest1,$start_age_date_latest2);
                                    $current_patient_dob_diffend = $diff_altedt->format("%a");

                                     /* for end_age */

                                      if($current_patient_dob>=$current_patient_dob_diffstart && $current_patient_dob<=$current_patient_dob_diffend)
                                      {
                                        
                                          $mobile_no=$vaccine_patient->mobile_no;
                                          $patient_name= $vaccine_patient->patient_name;
                                          $patient_email= $vaccine_patient->patient_email;

                                          $vaccination_date=date('d-m-Y',strtotime($next_date_of_vaccine));

                                          if(!empty($vaccine_patient->mobile_no))
                                          {
                                            
                                             send_sms('pedic_vaccine_pres',27,$vaccine_patient->patient_name,$vaccine_patient->mobile_no,array('{Patientname}'=>$vaccine_patient->patient_name,'{vaccination_date}'=>$vaccination_date));
                                          }
                                          if(!empty($vaccine_patient->patient_email))
                                          {

                                            $this->load->library('general_functions');
                                           $this->general_functions->email($vaccine_patient->patient_email,'','','','','1','pedic_vaccine_pres','27',array('{patient_name}'=>$vaccine_patient->patient_name,'{vaccination Date}'=>$vaccination_date));

                                          }

                                      }
                                       // for week only
                                      
                                    }
                                    if($ages->start_age_type==3)
                                    {  //for month //
                                      $date1=date_create($vaccine_patient->dob);
                                      $date2=date_create($next_date_of_vaccine);
                                      $diff = $date1->diff($date2, true);
                                      $current_patient_dob = $diff->format("%a");



                                          /* for start_age */
                                    $start_age_date_latest1= date_create(date('Y-m-d', strtotime($date_new. ' - '.$ages->start_age.' month')));
                                    $start_age_date_latest2 = date_create(date('Y-m-d'));
                                    $diff_altedt=date_diff($start_age_date_latest1,$start_age_date_latest2);
                                    $current_patient_dob_diffstart = $diff_altedt->format("%a");

                                     /* for start_age */


                                       /* for end age */
                                    $start_age_date_latest1= date_create(date('Y-m-d', strtotime($date_new. ' - '.$ages->end_age.' month')));
                                    $start_age_date_latest2 = date_create(date('Y-m-d'));
                                    $diff_altedt=date_diff($start_age_date_latest1,$start_age_date_latest2);
                                    $current_patient_dob_diffend = $diff_altedt->format("%a");

                                     /* for end_age */


                                      //$current_patient_dob = $diff->format("%a"); 
                                     // echo $diff->format("%R%a month");die;
                                     if($current_patient_dob>=$current_patient_dob_diffstart && $current_patient_dob<=$current_patient_dob_diffend)
                                      {
                                          $mobile_no=$vaccine_patient->mobile_no;
                                          $patient_name= $vaccine_patient->patient_name;
                                          $patient_email= $vaccine_patient->patient_email;

                                          $vaccination_date=date('d-m-Y',strtotime($next_date_of_vaccine));

                                          if(!empty($vaccine_patient->mobile_no))
                                          {

                                            send_sms('pedic_vaccine_pres',27,$vaccine_patient->patient_name,$vaccine_patient->mobile_no,array('{Patientname}'=>$vaccine_patient->patient_name,'{vaccination_date}'=>$vaccination_date));
                                          }
                                          if(!empty($vaccine_patient->patient_email))
                                          {

                                            $this->load->library('general_functions');
                                            $this->general_functions->email($vaccine_patient->patient_email,'','','','','1','pedic_vaccine_pres','27',array('{patient_name}'=>$vaccine_patient->patient_name,'{vaccination Date}'=>$vaccination_date));

                                          }

                                      }
                                       // for month only
                                      
                                    }
                                    if($ages->start_age_type==4)
                                    { 
                                    $date1=date_create($vaccine_patient->dob);
                                    $date2=date_create($next_date_of_vaccine);
                                    $diff = $date1->diff($date2, true);
                                    $current_patient_dob = $diff->format("%a");

                                          /* for start_age */
                                    $start_age_date_latest1= date_create(date('Y-m-d', strtotime($date_new. ' - '.$ages->start_age.' year')));
                                    $start_age_date_latest2 = date_create(date('Y-m-d'));
                                    $diff_altedt=date_diff($start_age_date_latest1,$start_age_date_latest2);
                                    $current_patient_dob_diffstart = $diff_altedt->format("%a");

                                     /* for start_age */


                                       /* for end age */
                                    $start_age_date_latest1= date_create(date('Y-m-d', strtotime($date_new. ' - '.$ages->end_age.' year')));
                                    $start_age_date_latest2 = date_create(date('Y-m-d'));
                                    $diff_altedt=date_diff($start_age_date_latest1,$start_age_date_latest2);
                                    $current_patient_dob_diffend = $diff_altedt->format("%a");

                                     /* for end_age */
                                     
                                     if($current_patient_dob>=$current_patient_dob_diffstart && $current_patient_dob<=$current_patient_dob_diffend)
                                      {
                                        $mobile_no=$vaccine_patient->mobile_no;
                                          $patient_name= $vaccine_patient->patient_name;
                                          $patient_email= $vaccine_patient->patient_email;

                                          $vaccination_date=date('d-m-Y',strtotime($next_date_of_vaccine));

                                          if(!empty($vaccine_patient->mobile_no))
                                          {

                                             send_sms('pedic_vaccine_pres',27,$vaccine_patient->patient_name,$vaccine_patient->mobile_no,array('{Patientname}'=>$vaccine_patient->patient_name,'{vaccination_date}'=>$vaccination_date));
                                          }
                                          if(!empty($vaccine_patient->patient_email))
                                          {

                                            $this->load->library('general_functions');
                                            $this->general_functions->email($vaccine_patient->patient_email,'','','','','1','pedic_vaccine_pres','27',array('{patient_name}'=>$vaccine_patient->patient_name,'{vaccination Date}'=>$vaccination_date));

                                          }
                                      }
                                      
                                       //for year //
                                    }
                                   
                                } 
                             }

                             // // age data end here////
       }
     }

       /* age new loop end here */



       /*foreach($dob_s as $birth_date)
      {
        $date_new= $birth_date['dob'];
        $vaccine_related_age= $this->prescription_model->vaccinerelatedage();
        $i=0;
        $end_date_interval=array();
         foreach($vaccine_related_age as $vaccine)
         {
            $j=0;
             $data[$i]['vaccine_name']=$vaccine['vaccination_name'];
             foreach($vaccine['age_list'] as $checked_age_list)
             {
             
                if($checked_age_list->start_age_type==1 && $checked_age_list->end_age_type==1)  //1 for days
                {
                    if($checked_age_list->start_age!='' && $checked_age_list->title!='')
                    {
                     
                    $end_date_interval[$j]['start_age']= date('Y-m-d', strtotime($date_new. ' + '.$checked_age_list->start_age.'day'));

                    }
                  else
                  {
                    $end_date_interval[$j]['start_age']='';  
                  }
                  if($checked_age_list->end_age!='' && $checked_age_list->title=='birth')
                  {

                    $end_date_interval[$j]['end_age']= date('Y-m-d', strtotime($date_new. ' + '.$checked_age_list->end_age.'day'));
                  }
                  else
                  {
                    $end_date_interval[$j]['end_age']='';
                  }

                }
              //print_r($users_vccines->mobile_no);
                foreach($end_date_interval as $date_interval)
                {

                if(!empty($date_interval))
                {
                	$checked_user_already_used_vaccine= $this->prescription_model->checked_vaccine_already_user($checked_age_list->age_id,$checked_age_list->vaccine_id,$birth_date['patient_id']);
                  
                if(($date_interval['start_age']==$next_date_of_vaccine)||($date_interval['end_age']==$next_date_of_vaccine))
                {
                
                  if(!empty($users_vccines->mobile_no))
                     {
                     	$mobile_no=$users_vccines->mobile_no;
                     	$patient_name=$users_vccines->patient_name;
                     	$vaccination_date=$next_date_of_vaccine;
                        send_sms('pedic_vaccine_pres',27,$patient_name,$mobile_no,array('{patient_name}'=>$patient_name,'{vaccination Date}'=>$vaccination_date)); 

                    }
                }
                else
                {
                  
                	//return false;
                 }
               }
              }
            }
        }
    }*/
    }
    

    function checkuserVaccine_old()
    {
       $data['uservaccinelist']= $this->prescription_model->getopdPatient();
       $DOB_B=array();
       $DOB_s=array();
       $nextdateofvaccine = date('d-m-Y', strtotime("+1 day"));

       $i=0;
       foreach($data['uservaccinelist'] as $usersVaccines)
       {
        if($usersVaccines->dob=="0000-00-00" || $usersVaccines->dob=="1970-01-01")
          {
           $DOB_B[$i]=$usersVaccines->dob;
          }
          else
          {
            $DOB_s[$i]['mobile_no']=$usersVaccines->mobile_no;
            $DOB_s[$i]['dob']=$usersVaccines->dob;
            $DOB_s[$i]['patient_name']=$usersVaccines->patient_name;
            $DOB_s[$i]['patient_email']=$usersVaccines->patient_email;
            $DOB_s[$i]['patient_id']=$usersVaccines->id;
          }
          $i++;

       }

      
      $patient_email='';
      $patient_name='';
      $mobile_no='';
      $date_new='';
      
      foreach($DOB_s as $birth_date)
      {
        $date_new= $birth_date['dob'];
        
        $vaccinerelatedage= $this->prescription_model->vaccinerelatedage();
        $i=0;
        $end_date_interval=array();
         foreach($vaccinerelatedage as $vaccine)
         {
            $j=0;
             $data[$i]['vaccine_name']=$vaccine['vaccination_name'];
             foreach($vaccine['age_list'] as $checkedagelist)
             {
              //////////checked vaccine used////////////
                $checkeduseralreadyusedvaccine= $this->prescription_model->checked_vaccine_already_user($checkedagelist->age_id,$checkedagelist->vaccination_id,$birth_date['patient_id']);

                //////////checked vaccine used////////////

                if($checkedagelist->start_age_type==1 && $checkedagelist->end_age_type==1)  //1 for days
                {
                    if($checkedagelist->start_age!='' && $checkedagelist->title!=0)
                    {

                    $end_date_interval[$j]['start_age']= date('Y-m-d', strtotime($date_new. ' + '.$checkedagelist->start_age.' day'));

                    }
                  else
                  {
                    $end_date_interval[$j]['start_age']='';  
                  }
                  if($checkedagelist->end_age!='' && $checkedagelist->title=='birth')
                  {

                    $end_date_interval[$j]['end_age']= date('Y-m-d', strtotime($date_new. ' + '.$checkedagelist->end_age.' day'));
                  }
                  else
                  {
                    $end_date_interval[$j]['end_age']='';
                  }

                }
                
             }
            print '<pre>';print_r($end_date_interval);die;
             $j++;

            $i++;
         }

        //print '<pre>';print_r($vaccinerelatedage);die;
          //$dateofbirthsaccage= $this->prescription_model->date_age_list($birth_date['dob']);


          
           // foreach($dateofbirthsaccage as $ages)
           // {
           //    if($ages['end_age']==$nextdateofvaccine)
           //    {
           //       if(!empty($birth_date['patient_email']))
           //       {
           //        $patient_email= $birth_date['patient_email'];
           //       }
           //       if(!empty($birth_date['patient_name']))
           //       {
           //        $patient_name= $birth_date['patient_name'];
           //       }
           //       if(!empty($birth_date['mobile_no']))
           //       {
           //        $mobile_no= $birth_date['mobile_no'];
           //       }
           //      if(!empty($mobile_no))
           //          {
           //            send_sms('pedic_vaccine_pres',27,$patient_name,$mobile_no,array('{patient_name}'=>$patient_name,'{vaccination Date}'=>$vaccination_date)); 
           //          }
           //    }
           // }
      }
    }

    public function send_email($booking_id="",$date_sec="",$patient_id='',$type='')
    { 
        //unauthorise_permission(101,641);
        if(!empty($booking_id) && $booking_id>0)
        {
            $data['page_title'] = 'Send Email';   
            $this->load->model('general/general_model'); 
            
              /* code for chart */


              /* code for chart */
            $data['form_error'] = [];
            $data['sign_error'] = [];
            $post = $this->input->post();
            $data['form_data'] = array(
                                         'booking_id'=>$booking_id,
                                         'patient_id'=>$patient_id,
                                         'date_sec'=>$date_sec,
                                         'type'=>$type,
                                         'subject'=>'',
                                         'email'=>'',
                                         'message'=>'',
                                         'email'=>''
                                      );
            if(isset($post) && !empty($post))
            {   
                

                $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
                $this->form_validation->set_rules('email', 'Email', 'trim|required');
                $this->form_validation->set_rules('message', 'Message', 'trim|required'); 
                $this->form_validation->set_rules('subject', 'Subject', 'trim|required'); 

              if($this->form_validation->run() == TRUE)
              {
              //print_r($post); exit;
                $booking_id = $post['booking_id'];
                $type = $post['type'];
              //create pdf

                $this->pdf_vaccination_details($booking_id,$date_sec,$patient_id,1,'mail_send');
                $email = $post['email'];
                $subject = $post['subject'];
                $message = $post['message'];

                $file_name = "vaccination_report_".$booking_id.".pdf";
                $attachment = DIR_UPLOAD_PATH.'temp/'.$file_name; 
                $this->load->library('general_library');


                $response = $this->general_library->email($email,$subject,$message,'',$attachment,'','','','',$booking_data->branch_id);

                if(!empty($attachment) && file_exists($attachment)) 
                {
                  unlink($attachment);
                } 

                echo 1;
                return false;


              }
               else
               {
                  $data['form_data'] = array(
                                            'booking_id'=>$post['booking_id'],
                                            'patient_id'=>$patient_id,
                                            'date_sec'=>$date_sec,
                                            'type'=>$post['type'],
                                            'email'=>$post['email'],
                                            'subject'=>$post['subject'],
                                            'message'=>$post['message'],
                                         );

                  $data['form_error'] = validation_errors();
               }  

                  
                     

            }

            $this->load->view('pediatrician/pediatrician_prescription/send_email',$data);
        } 
      }

    public function billing_vaccine($booking_id="",$patient_id="")
    {
        
        $this->session->unset_userdata('billing_vaccine_ids');
        $this->load->model('general/general_model'); 
        $data['page_title']="Vaccination Billing";
        $data['payment_mode']=$this->general_model->payment_mode();
        $post =  $this->input->post(); 
        $data['form_data']=array();
        //echo "<pre>"; print_r($post); exit;
        $data['attended_doctor'] = $this->ipd_booking->attended_doctor_list();
        if(isset($post['edit_id']) && !empty($post['edit_id']) && is_numeric($post['edit_id']))
        {  
          $result = $this->prescription_model->get_by_id_prescription($post['edit_id']); 
          $data['edit_id']=$post['edit_id'];
          $result_medince_list = $this->prescription_model->get_by_id_peditration_vaccine($post['edit_id'],$result['total_amount']); 
          $this->session->set_userdata('billing_vaccine_ids',$result_medince_list);

            if(!empty($result))
            {
              if(!empty($result['vaccination_date_time']))
              {
                if(($result['vaccination_date_time']=='0000-00-00 00:00:00')&&($result['vaccination_date_time']=='01-01-1970 05:30:00'))
                {
                  $vaccination_date_time='';
                }
                else
                {
                 $vaccination_date_time=date('d-m-Y H:i:s',strtotime($result['vaccination_date_time']));
                }
              }
            }
             $data['form_data'] = array(
                                   'booking_id'=>$post['booking_id'],
                                  'patient_id'=>$post['patient_id'],
                                 
                                  'vaccination_date_time'=>$vaccination_date_time,
                                  'attended_doctor'=>$result['attended_doctor'],
                                  'total_amount'=>$result['total_amount'],
                                  'discount_percent'=>$result['discount_percent'],
                                  'discount_amount'=>$result['discount_amount'],
                                  'cgst_amount'=>$result['cgst'],
                                  'igst_amount'=>$result['igst'],
                                  'sgst_amount'=>$result['sgst'],
                                  'net_amount'=>$result['net_amount'],
                                  'pay_amount'=>$result['paid_amount'],
                                  "field_name"=>'',
                                  'balance'=>$result['balance']
                                 
                                  ); 
              //print_r($data['form_data']);die;

            $medicine_list = $this->session->userdata('billing_vaccine_ids');
           
            $data['billing_vaccine_ids'] = $medicine_list;
            $data['id'] = $post['edit_id'];

            if(!empty($medicine_list))
            { 
            $medicine_id_arr = [];
            $age_ids=[];
            foreach($medicine_list as $key=>$medicine_sess)
            {
            $imp_key = explode('.', $key);
            $medicine_id_arr[] = $imp_key[0];
            $medicine_batch_arr[] = $imp_key[1];
            $age_ids=$medicine_sess['age_id'];
            } 
            //echo "<pre>";print_r($medicine_list);die;
            $medicine_ids = implode(',', $medicine_id_arr);
            $medicine_batchs = implode(',', $medicine_batch_arr);
            $data['medicne_new_list'] = $this->prescription_model->medicine_list($medicine_id_arr,$medicine_batch_arr,$age_ids);

            }
        }
        else
        {
            
         $this->load->model('opd/opd_model','opd'); 
        $opd_booking_data = $this->opd->get_by_id($post['booking_id']);  
        //echo "<pre>"; print_r($opd_booking_data); exit;
          $data['edit_id']='';
          $data['form_data'] = array(
                                  'booking_id'=>$post['booking_id'],
                                  'patient_id'=>$post['patient_id'],
                                 
                                  'vaccination_date_time'=>"",
                                  'attended_doctor'=>$opd_booking_data['attended_doctor'],
                                  'total_amount'=>"",
                                  'discount_percent'=>"",
                                  'discount_amount'=>"",
                                  'cgst_amount'=>"",
                                  'igst_amount'=>"",
                                  'sgst_amount'=>"",
                                  'net_amount'=>"",
                                  'pay_amount'=>"",
                                  "field_name"=>'',
                                  'balance'=>""); 
        }
       
        $data['page_title']='Billing';

        $row='';
        $i=1;
        /* set session data for pres */
        $vaccine_id='';
        // $data['form_data']=array(
        //                           'booking_id'=>$post['booking_id'],
        //                           'patient_id'=>$post['patient_id'],
        //                           'vaccination_date_time'=>'',
        //                           'attended_doctor'=>'',
        //                           'age_id'=>$post['age_id']

        //                           );

        if(!empty($post))
        {
        $post_mid_arr = [];
        $m_new_array_id=[];

        if(isset($post['vaccine_ids']) && !empty($post['vaccine_ids']))
            {
            $purchase = $this->session->userdata('billing_vaccine_ids');

            $vaccine_id = [];
            $mid_arr = [];

            if(isset($purchase) && !empty($purchase))
                { 
                $total_price_medicine_amount=0;
                foreach($post['vaccine_ids'] as $m_ids)
                {

                    $m_id_arr = explode('.',$m_ids);
                    $vat='';
                    $medicine_data = $this->prescription_model->medicine_list($m_id_arr[0],$m_id_arr[1],$m_id_arr[2]);

                    $per_pic_amount= $medicine_data[0]->m_rp/$medicine_data[0]->conversion;
                    $tot_qty_with_rate= $per_pic_amount*1;

                    $total_discount = ($medicine_data[0]->discount/100)*$tot_qty_with_rate;
                    $total_amount= $tot_qty_with_rate-$total_discount;
                    $cgstToPay = ($total_amount / 100) * $medicine_data[0]->cgst;
                    $igstToPay = ($total_amount / 100) * $medicine_data[0]->igst;
                    $sgstToPay = ($total_amount / 100) * $medicine_data[0]->sgst;
                    $total_tax= $cgstToPay+$igstToPay+$sgstToPay;
                    $total_price_medicine_amount =  $total_price_medicine_amount+$total_amount-$total_tax;
                    if(strtotime($medicine_data[0]->manuf_date)>316000)
                    {
                      $manuf_date=date('d-m-Y',strtotime($medicine_data[0]->manuf_date));
                    }
                    else
                    {
                      $manuf_date='';
                    } 

                    if(strtotime($medicine_data[0]->expiry_date)>315000)
                    {
                      $exp_date=date('d-m-Y',strtotime($medicine_data[0]->expiry_date));
                    }
                    else
                    {
                    $exp_date='';
                    }
                    $post_mid_arr[$m_id_arr[0].'.'.$m_id_arr[1]] = array('vid'=>$m_id_arr[0], 'batch_no'=>$m_id_arr[1], 'qty'=>'1', 'exp_date'=>$exp_date,'manuf_date'=>$manuf_date,'discount'=>$medicine_data[0]->discount,'bar_code'=>$medicine_data[0]->bar_code,'conversion'=>$medicine_data[0]->conversion,'hsn_no'=>$medicine_data[0]->hsn,'cgst'=>$medicine_data[0]->cgst,'sgst'=>$medicine_data[0]->sgst,'igst'=>$medicine_data[0]->igst, 'per_pic_amount'=>$per_pic_amount,'sale_amount'=>$medicine_data[0]->mrp,'total_amount'=>$total_amount,'age_id'=>$medicine_data[0]->age_id,'total_pricewith_medicine'=>$total_price_medicine_amount); 
                  } 

                $vaccine_id = $purchase+$post_mid_arr;

                } 
            else
            { 
            $total_price_medicine_amount=0;

            foreach($post['vaccine_ids'] as $m_ids)
            {
           
            $m_id_arr = explode('.',$m_ids);

            $medicine_data = $this->prescription_model->medicine_list($m_id_arr[0],$m_id_arr[1],$m_id_arr[2]);




            $per_pic_amount= $medicine_data[0]->m_rp/$medicine_data[0]->conversion;
            $tot_qty_with_rate= $per_pic_amount*1;
            if(strtotime($medicine_data[0]->manuf_date)>31536000)
            {
            $manuf_date=date('d-m-Y',strtotime($medicine_data[0]->manuf_date));
            }
            else
            {
            $manuf_date='';
            } 

            if(strtotime($medicine_data[0]->expiry_date)>31536000)
            {
            $exp_date=date('d-m-Y',strtotime($medicine_data[0]->expiry_date));
            }
            else
            {
            $exp_date='';
            } 
            $total_discount = ($medicine_data[0]->discount/100)*$tot_qty_with_rate; 
            $total_amount= $tot_qty_with_rate-$total_discount;
            $cgstToPay = ($total_amount / 100) * $medicine_data[0]->cgst;
            $igstToPay = ($total_amount / 100) * $medicine_data[0]->igst;
            $sgstToPay = ($total_amount / 100) * $medicine_data[0]->sgst;
            $total_tax= $cgstToPay+$igstToPay+$sgstToPay;

            $total_price_medicine_amount =  $total_price_medicine_amount+$total_amount-$total_tax; 

            $post_mid_arr[$m_id_arr[0].'.'.$m_id_arr[1]] = array('vid'=>$m_id_arr[0],'batch_no'=>$m_id_arr[1], 'qty'=>'1', 'exp_date'=>$exp_date,'bar_code'=>$medicine_data[0]->bar_code,'manuf_date'=>$manuf_date,'per_pic_amount'=>$per_pic_amount,'conversion'=>$medicine_data[0]->conversion,'hsn_no'=>$medicine_data[0]->hsn,'sale_amount'=>$medicine_data[0]->mrp,'per_pic_amount'=>$per_pic_amount,'discount'=>$medicine_data[0]->discount,'cgst'=>$medicine_data[0]->cgst,'sgst'=>$medicine_data[0]->sgst,'igst'=>$medicine_data[0]->igst,'age_id'=>$medicine_data[0]->age_id,'total_amount'=>$total_amount,'total_pricewith_medicine'=>$total_price_medicine_amount);
            //print_r($post_mid_arr);die;  
            }
            $vaccine_id = $post_mid_arr;
            }  
            $this->session->set_userdata('billing_vaccine_ids',$vaccine_id);
            }
        

        /* set session data for pres*/

        $medicine_sess = $this->session->userdata('billing_vaccine_ids');
        
        $batch_no='';
        $result_medicine = [];
        if(!empty($medicine_sess))
        { 
        $ids_arr= [];
        $age_ids=[];
        foreach($medicine_sess as $key_m_arr=>$m_arr)
        {
        $imp_data = explode(".", $key_m_arr);
        $ids_arr[] = $imp_data[0];
        $batch_arr[] = $imp_data[1];
        $age_ids[] = $m_arr['age_id'];
        }

        $result_medicine = $this->prescription_model->medicine_list($ids_arr,$batch_arr,$age_ids);

        
        }
        $this->load->model('sales_vaccination/sales_vaccination_model','sales_vaccination');
        $table='<div class="left">';
        $table.='<table class="table table-bordered table-striped m_pur_tbl1">';
        $table.='<thead class="bg-theme">';
        $table.='<tr>';
        $table.='<th class="40" align="center">';
        $table.='<input type="checkbox" name="" onClick="toggle_new(this);">';
        $table.='</th>';
        $table.='<th>Vaccination Name</th>';
        $table.='<th>Vaccination Code</th>';
        $table.='<th>HSN No.</th>';
        $table.='<th>Packing</th>';
        $table.='<th>Batch No.</th>';
        
        $table.='<th>Mfg. Date</th>';
        $table.='<th>Exp. Date</th>';
        $table.='<th>Barcode</th>';
        $table.='<th>Quantity</th>';
        
        $table.='<th>MRP</th>';
        
        $table.='<th>Discount(%)</th>';
        $table.='<th>CGST(%)</th>';
        $table.='<th>SGST(%)</th>';
        $table.='<th>IGST(%)</th>';
        $table.='<th>Total</th>';
        $table.='</tr>';
        $table.='</thead>';
        $table.='<tbody>';
        //print_r($result_medicine);die;
        if(count($result_medicine)>0 && isset($result_medicine) || !empty($ids)){

        foreach($result_medicine as $vaccination){
        $batch_no=0;

        if($medicine_sess[$vaccination->id.'.'.$batch_no]["exp_date"]=="00-00-0000")
        {

        $date_new='';
        }else{
        $date_new=$medicine_sess[$vaccination->id.'.'.$batch_no]["exp_date"];
        }
        if($medicine_sess[$vaccination->id.'.'.$batch_no]["manuf_date"]=="00-00-0000"){

        $date_newmanuf='';
        }else{
        $date_newmanuf=$medicine_sess[$vaccination->id.'.'.$batch_no]["manuf_date"];
        }

        $varids="'".$vaccination->id.$batch_no."'";

        $value="'".$vaccination->id.".".$batch_no."'";

        $check_script='';
        $check_script1='';
        $check_script= "<script>
                          $('#expiry_date_".$vaccination->id.$batch_no."').datepicker({
                          format: 'dd-mm-yyyy',
                          autoclose: true,
                          startDate: '".$date_new."'
                          });
                        </script>";
        $check_script1= "<script>
                        $('#manuf_date_".$vaccination->id.$batch_no."').datepicker({
                        format: 'dd-mm-yyyy',
                        autoclose: true,
                        endDate: '".$date_newmanuf."',
                      })</script>";

        
        $qty_data = $this->sales_vaccination->get_batch_med_qty($vaccination->id,$vaccination->batch_no);
        //print_r($qty_data);die;
        if($qty_data['total_qty']>0)
        {
        $table.='<tr><input type="hidden" id="vaccine_id_'.$vaccination->id.$batch_no.'" name="m_id[]" value="'.$vaccination->id.'.'.$batch_no.'"/>
        
        <input type="hidden" id="vaccine_s_id_'.$vaccination->id.'" name="ms_id[]" value="'.$vaccination->id.'"/>
        
        <input type="hidden" id="batch_s_id_'.$vaccination->id.'" name="msb_id[]" value="'.$batch_no.'"/>
        
        
        <input type="hidden" id="mbid_'.$vaccination->id.$batch_no.'" name="mbid[]" value='.$value.'/>
        <input type="hidden" id="purchase_rate_mrp'.$vaccination->id.$batch_no.'" name="purchase_rate_mrp[]" value="'.$vaccination->mrp.'"/><input type="hidden" id="batch_no_'.$vaccination->id.$batch_no.'" name="batch_no[]" value="'.$batch_no.'"/><input type="hidden" id="conversion_'.$vaccination->id.$batch_no.'" name="conversion[]" value="'.$vaccination->conversion.'"/><input type="hidden" id="age_id_'.$vaccination->id.$batch_no.'" name="age_id[]" value="'.$vaccination->age_id.'"/>';

        $table.='<td><input type="checkbox" name="vaccine_id[]" class="booked_checkbox" value='.$value.'></td>';
        $table.='<td>'.$vaccination->vaccination_name.'</td>';
        $table.='<td>'.$vaccination->vaccination_code.'</td>';

        $table.='<td><input type="text" id="hsn_no_'.$vaccination->id.$batch_no.'" name="hsn_no[]" value="'.$medicine_sess[$vaccination->id.'.'.$batch_no]["hsn_no"].'" onkeyup="payment_cal_perrow('.$varids.');"/></td>';

        $table.='<td>'.$vaccination->packing.'</td>';
        $table.='<td>'.$batch_no.'</td>';
        // $table.='<td>'.$vaccination->conversion.'</td>';
        $table.='<td><input type="text" value="'.$date_newmanuf.'" name="manuf_date[]" class="" placeholder="Manufacture date" style="width:84px;" id="manuf_date_'.$vaccination->id.$batch_no.'" onchange="payment_cal_perrow('.$varids.');"/>'.$check_script1.'</td>';

        $table.='<td><input type="text" value="'.$date_new.'" name="expiry_date[]" class="" placeholder="Expiry date" style="width:84px;" id="expiry_date_'.$vaccination->id.$batch_no.'"/>'.$check_script.'</td>';

        $table.='<td><input type="text"  value="'.$medicine_sess[$vaccination->id.'.'.$batch_no]["bar_code"].'" name="bar_code[]" class="" placeholder="Bar Code" style="width:84px;" id="bar_code_'.$vaccination->id.$batch_no.'" onkeyup="payment_cal_perrow('.$varids.');validation_bar_code('.$varids.');"/><div  id="barcode_error_'.$vaccination->id.$batch_no.'"  style="color:red;"></td>';

        $table.='<td><input type="text" name="quantity[]" placeholder="Qty" style="width:59px;" id="qty_'.$vaccination->id.'" value="'.$medicine_sess[$vaccination->id.'.'.$batch_no]["qty"].'" onkeyup="payment_cal_perrow('.$varids.'),validation_check(0,'.$vaccination->id.');"/><div  id="unit1_error_'.$vaccination->id.$batch_no.'"  style="color:red;"></div><div  id="unit1_error_'.$vaccination->id.'"  style="color:red;"></div></td>';
        //$table.='<td>'.$vaccination->medicine_unit_2.'</td>';
        /* $table.='<td></td>';*/
        $table.='<td><input type="text" id="mrp_'.$vaccination->id.$batch_no.'" name="mrp[]" value="'.number_format($medicine_sess[$vaccination->id.'.'.$batch_no]["per_pic_amount"],2,'.','').'" onkeyup="payment_cal_perrow('.$varids.');"/></td>';
        // $table.='<td>'.$vaccination->purchase_rate.'</td>';
        $table.='<td><input type="text" class="price_float" name="discount[]" placeholder="Discount" style="width:59px;" id="discount_'.$vaccination->id.$batch_no.'" value="'.$medicine_sess[$vaccination->id.'.'.$batch_no]["discount"].'" onkeyup="payment_cal_perrow('.$varids.');"/></td>';

        $table.='<td><input type="text" class="price_float" name="cgst[]" placeholder="CGST" style="width:59px;" value="'.$medicine_sess[$vaccination->id.'.'.$batch_no]["cgst"].'" id="cgst_'.$vaccination->id.$batch_no.'" onkeyup="payment_cal_perrow('.$varids.');"/></td>';

        $table.='<td><input type="text" class="price_float" name="sgst[]" placeholder="SGST" style="width:59px;" value="'.$medicine_sess[$vaccination->id.'.'.$batch_no]["sgst"].'" id="sgst_'.$vaccination->id.$batch_no.'" onkeyup="payment_cal_perrow('.$varids.');"/></td>';
        $table.='<td><input type="text" class="price_float" name="igst[]" placeholder="IGST" style="width:59px;" value="'.$medicine_sess[$vaccination->id.'.'.$batch_no]["igst"].'" id="igst_'.$vaccination->id.$batch_no.'" onkeyup="payment_cal_perrow('.$varids.');"/></td>';

        $table.=' <td><input type="text" value="'.number_format($medicine_sess[$vaccination->id.'.'.$batch_no]["total_amount"],2).'" name="total_amount[]" placeholder="Total" style="width:59px;" readonly id="total_amount_'.$vaccination->id.$batch_no.'" /></td>';

        $table.='</tr>';
        // }
        }
        else
        {
           $table.='<tr><td colspan="15" class="text-danger"><div class="text-center">Vaccine Out of Stock now.</div></td></tr>'; 
        }
        }
        }else{
        $table.='<td colspan="15" class="text-danger"><div class="text-center">No record found</div></td>';
        }
        $table.='</tbody>';
        $table.='</table>';
        $table.='</div>';
        $table.='<div class="right">';
        //$table.='<a class="btn-new" onclick="medicine_list_vals();">Delete</a>';
        $table.='</div>'; 
        $row=$table;


        $data['medicine_list'] = $row;

        //print '<pre>'; print_r($row);die;
        // $output=array('data'=>$table);
        // echo json_encode($output);
}
        $this->load->view('pediatrician/pediatrician_prescription/billing_vaccine',$data);
     }


    public function payment_cal_perrow()
    {
      $this->load->model('sales_vaccination/sales_vaccination_model','sales_vaccination');
      $this->load->model('vaccination_entry/vaccination_entry_model','vaccination_entry');
      $post = $this->input->post();
      $total_price_medicine_amount=0;
      $total_amount=0;
      $cgstToPay=0;
      $total_tax=0;
      //$medicine_list = $this->session->userdata('billing_vaccine_ids');
       $medicine_list = $this->session->userdata('billing_vaccine_ids');
       // echo "<pre>"; print_r($post); exit;
       if(isset($post) && !empty($post))
       {
            $total_amount = 0;
            $medicine_id_new= explode('.',$post['vaccine_id']);
            $age_ids= $post['age_id'];

            $medicine_data = $this->sales_vaccination->medicine_list($medicine_id_new[0],$medicine_id_new[1], $age_ids);
            //echo "<pre>"; print_r($medicine_data); exit;
            $per_pic_amount= $post['mrp'];
            $tot_qty_with_rate= $per_pic_amount*$post['qty'];
            $total_discount = ($post['discount']/100)*$tot_qty_with_rate;
            $total_amount= $tot_qty_with_rate-$total_discount;
             $cgstToPay = ($total_amount / 100) * $post['cgst'];
             $igstToPay = ($total_amount / 100) * $post['igst'];
             $sgstToPay = ($total_amount / 100) * $post['sgst'];
             $total_tax= $cgstToPay+$igstToPay+$sgstToPay;
           
             $total_price_medicine_amount =  $total_price_medicine_amount+$total_amount-$total_tax;
            

            $medicine_list[$post['mbid']] =  array('vid'=>$medicine_id_new[0],'age_id'=>$post['age_id'], 'qty'=>$post['qty'],'batch_no'=>$medicine_id_new[1],'bar_code'=>$post['bar_code'],'manuf_date'=>$post['manuf_date'],'exp_date'=>$post['expiry_date'],'sgst'=>$post['sgst'],'igst'=>$post['igst'],'hsn_no'=>$post['hsn_no'],'cgst'=>$post['cgst'],'discount'=>$post['discount'],'sale_amount'=>$post['mrp'],'per_pic_amount'=>$per_pic_amount,'conversion'=>$post['conversion'],'total_amount'=>$total_amount,'total_pricewith_medicine'=>$total_price_medicine_amount); 
            $this->session->set_userdata('billing_vaccine_ids', $medicine_list);
            //print_r($this->session->userdata('billing_vaccine_ids'));die;
            $pay_arr = array('total_amount'=>number_format($total_amount,2));
            $json = json_encode($pay_arr,true);
            //echo $this->session->userdata('sale_gross_amount');
            echo $json;
         
       }
    }
   public function payment_calc_all()
    { 
       $medicine_list = $this->session->userdata('billing_vaccine_ids');

       $this->load->model('sales_vaccination/sales_vaccination_model','sales_vaccination');
       if(!empty($medicine_list))
       {
        $post = $this->input->post();
        $total_amount = 0;
        $total_vat = 0;
        $total_discount =0;
        $net_amount =0;  
        //$total_cgst = 0;
        $total_igst = 0;
        $total_sgst = 0;
        //$totigst_amount=0;
       // $totcgst_amount=0;
       // $totsgst_amount=0;
        $total_discount =0;
        $totsgst_amount=0;
        $net_amount =0;  
        $purchase_amount=0;
        $newamountwithigst=0;
        $newamountwithcgst=0;
        $newamountwithsgst=0;
        $total_new_amount=0;
        $tot_discount_amount=0;
         $payamount=0;
         $i=0;
        //$total_new_other_amount=0;
        foreach($medicine_list as $vaccination)
        {    

            //print_r($medicine_list);die;
            $per_pic_amount= $vaccination['per_pic_amount'];
            $tot_qty_with_rate= $per_pic_amount*$vaccination['qty']; 
            $total_new_other_amount= $tot_qty_with_rate-($tot_qty_with_rate/100)*$vaccination['discount'];
            $total_new_amount= $total_new_amount+$vaccination['total_pricewith_medicine'];
            
            $totigst_amount = $vaccination['igst'];
            $total_amountwithigst= ($total_new_other_amount/100)*$totigst_amount;

            $newamountwithigst=$newamountwithigst+ $total_amountwithigst;

           // echo $newamountwithigst;
          /* amount with cgst */

           
            $totcgst_amount = $vaccination['cgst'];
            $total_amountwithcgst= ($totcgst_amount/100)*$total_new_other_amount;
          //echo $total_amountwithcgst;
            //echo $total_amountwithcgst;
            $newamountwithcgst=$newamountwithcgst+$total_amountwithcgst;
          // echo $newamountwithcgst;
            /* amount with cgst */


            $totsgst_amount = $vaccination['sgst']; 
            $total_amountwithsgst= ($total_new_other_amount/100)*$totsgst_amount; 
            $newamountwithsgst=$newamountwithsgst+ $total_amountwithsgst; 
            //echo $tot_qty_with_rate;
             $tot_discount_amount+= ($tot_qty_with_rate)/100*$vaccination['discount']; 
              $i++;
        } 
     
            if($post['discount']!='' && $post['discount']!=0){
            $total_discount = ($post['discount']/100)*$total_new_amount;}
            else{
               $total_discount=$tot_discount_amount;
            }
            $net_amount = ($total_new_amount-$total_discount)+$newamountwithsgst+$newamountwithcgst+$newamountwithigst;


           
            if($post['pay']==1 || $post['data_id']!=''){
            $payamount=$post['pay_amount'];
            }else{
            $payamount=$net_amount;
            }
            $blance_dues=$net_amount -$payamount;
            $blance_due = number_format($blance_dues,2,'.','');
            $newamountwithsgst = number_format($newamountwithsgst,2,'.','');
            $newamountwithcgst = number_format($newamountwithcgst,2,'.','');
            $newamountwithigst = number_format($newamountwithigst,2,'.','');
            $payamount = number_format($payamount,2,'.','');


         $pay_arr = array('total_amount'=>number_format($total_new_amount,2,'.',''), 'net_amount'=>number_format($net_amount,2,'.',''),'pay_amount'=>$payamount,'balance_due'=>$blance_due,'discount'=>$post['discount'],'sgst_amount'=>$newamountwithsgst,'igst_amount'=>$newamountwithigst,'cgst_amount'=> $newamountwithcgst,'discount_amount'=>number_format($total_discount,2,'.',''));
        $json = json_encode($pay_arr,true);
        echo $json;die;
        
       }
        
    } 

    public function check_stock_avability()
    {
        $this->load->model('sales_vaccination/sales_vaccination_model','sales_vaccination');
        $id= $this->input->post('mbid');
        //$explode_ids= explode('.',$id);
        $batch_no= $this->input->post('batch_no');
        $conversion= $this->input->post('conversion');
        $unit2= $this->input->post('unit2');
        $qty= $this->input->post('qty');
        //$explode_ids[0]
        $return= $this->sales_vaccination->check_stock_avability($id,$batch_no);
        $tot_val= $unit2;
        if($return >= $tot_val){
        echo '0'; 
        }else{
        echo '1';

        } 
  }
  
   public function print_growth($patient_id='',$growth_id='',$booking_id='',$branch_id='')
    {
        $this->load->model('opd/opd_model','opd');
        $data['page_title'] = "Print Growth";
        $data['growth_prescription_ipd_data'] = $this->prescription_model->get_by_opd_id($booking_id);
        $data['growth_prescription_data'] = $this->prescription_model->get_by_id($booking_id,$growth_id);
      $template_format = $this->opd->template_format(array('section_id'=>12,'types'=>4),$branch_id);
    // echo "<pre>";print_r($data);die();
      $data['template_data']=$template_format;
        $this->load->view('pediatrician/pediatrician_prescription/print_growth_template',$data);
    }
    
    
    public function mark_vaccination()
    {
        $post = $this->input->post();
        //echo "<pre>"; print_r($post); exit;
        $growth_booking_id=$this->prescription_model->mark_vaccination();
        $pay_arr = array('success'=>'1');
        $json = json_encode($pay_arr,true);
        echo $json;die;
    }
    
    function mark_vaccination_date($age_id,$vaccine_id,$booking_id,$patient_id,$attended_doctor)
    {
      
        $post = $this->input->post();
        //echo "<pre>"; print_r($post); exit;
        
        $data['age_id'] = $age_id;
        $data['vaccine_id'] = $vaccine_id;
        $data['booking_id'] = $booking_id;
        $data['patient_id'] = $patient_id;
        $data['attended_doctor'] = $attended_doctor;
        $data['booking_date'] = date('d-m-Y');
        $data['booking_date_time'] = date('H:i:s');

        $data['title'] = 'Mark Date';
        $this->load->view('pediatrician/pediatrician_prescription/mark_date', $data);
     
   }
   
   public function print_consolidate_prescription()
    {
        $users_data = $this->session->userdata('auth_users');
        $branch_id = $users_data['parent_id'];
         $get = $this->input->get();
         //echo "<pre>"; print_r($get); die;
         $data['page_title'] = 'Prescription List';
         if(isset($get) && !empty($get))
         {
           $all_prescription_ids = $get['pres_ids'];  
           $data['growth_prescriptiondata'] = $this->prescription_model->get_consolidate_prescription($all_prescription_ids);
          // echo "<pre>"; print_r($data['growth_prescription_data']); exit;
         }
         
    //$data['growth_prescription_ipd_data'] = $this->prescription_model->get_by_opd_id($booking_id);
        $this->load->model('opd/opd_model','opd');
      $template_format = $this->opd->template_format(array('section_id'=>12,'types'=>4),$branch_id);
    // echo "<pre>";print_r($data);die();
      $data['template_data']=$template_format;
      
        $this->load->view('pediatrician/pediatrician_prescription/consolidate_prescription',$data);
    }
    
   /* public function print_growth($patient_id='',$growth_id='',$booking_id='',$branch_id='')
    {
        $this->load->model('opd/opd_model','opd');
        $data['page_title'] = "Print Growth";
        $data['growth_prescription_ipd_data'] = $this->prescription_model->get_by_opd_id($booking_id);
        $data['growth_prescription_data'] = $this->prescription_model->get_by_id($booking_id,$growth_id);
      $template_format = $this->opd->template_format(array('section_id'=>12,'types'=>4),$branch_id);
    // echo "<pre>";print_r($data);die();
      $data['template_data']=$template_format;
        $this->load->view('pediatrician/pediatrician_prescription/print_growth_template',$data);
    }*/
// Please write code above this
}
?>
