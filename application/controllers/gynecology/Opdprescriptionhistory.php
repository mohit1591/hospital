<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Opdprescriptionhistory extends CI_Controller {
 
	function __construct() 
	{
  	
    parent::__construct();	
  	auth_users();  
  	$this->load->model('prescription/opdprescriptionhistory_model','prescriptionhistory');
  	$this->load->library('form_validation');
  }



  public function lists($patient_id='')
  {
      unauthorise_permission(19,113); 
      $data['page_title'] = 'OPD Prescription History'; 
      $data['patient_id'] = $patient_id; 
      $this->load->view('gynecology/prescription_history',$data);
  }

  public function ajax_list($patient_id='')
  { 
        unauthorise_permission(19,113);
        $users_data = $this->session->userdata('auth_users');
        $list = $this->prescriptionhistory->get_datatables($patient_id);  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $prescription) { 
            $no++;
            $row = array();
            if($prescription->status==1)
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
            //$row[] = '<input type="checkbox" name="prescription[]" class="checklist" value="'.$prescription->id.'">'.$check_script;
            $row[] = $prescription->booking_code;
            $row[] = $prescription->patient_code;    
            $row[] = $prescription->patient_name;
            $row[] = $prescription->mobile_no;
            
            $row[] = $status; 
            $row[] = date('d-M-Y H:i A',strtotime($prescription->created_date));  

            //Action button /////
            $btn_edit = ""; 
            $btn_view = ""; 
            $btn_delete = "";
            $btn_view_pre ="";
            $btn_print_pre="";
            $btn_upload_pre="";
            $btn_view_upload_pre="";
            
            //if(in_array('116',$users_data['permission']['action'])) 
            //{
               $btn_view_pre = ' <a class="btn-custom"  href="'.base_url('gynecology/gynecology_prescription/view_prescription/'.$prescription->flag_id.'/'.$prescription->branch_id).'" title="View Gynecology Prescription" data-url="512"><i class="fa fa-info-circle"></i> View Gynecology Prescription</a>';
            //} 
            
            //if(in_array('116',$users_data['permission']['action'])) 
            //{
              
                $print_url = "'".base_url('gynecology/gynecology_prescription/print_prescriptions/'.$prescription->flag_id.'/'.$prescription->branch_id)."'";
                $btn_print_pre = ' <a class="btn-custom" onClick="return print_window_page('.$print_url.')" href="javascript:void(0)" title="Print gynecology Prescription"  data-url="512"><i class="fa fa-print"></i> Print Gynecology Prescription</a>'; 
              
            //}

            /*if(in_array('116',$users_data['permission']['action'])) 
            {
               $btn_upload_pre = '<a class="btn-custom" onclick="return upload_prescription('.$prescription->id.')" href="javascript:void(0)" title="View"><i class="fa fa-info-circle"></i> Upload Prescription </a>';
            }

            if(in_array('116',$users_data['permission']['action'])) 
            {
               $btn_view_upload_pre = '<a class="btn-custom" href="'.base_url('/prescription/view_files/'.$prescription->id).'" title="View Prescription" data-url="512"><i class="fa fa-circle"></i> View Prescription Files</a>';


            }*/   
            
          
            // End Action Button //

            $row[] =   $btn_view_pre.$btn_print_pre.$btn_upload_pre.$btn_view_upload_pre.$btn_edit.$btn_view.$btn_delete;                
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->prescriptionhistory->count_all($patient_id),
                        "recordsFiltered" => $this->prescriptionhistory->count_filtered($patient_id),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

  


 }   