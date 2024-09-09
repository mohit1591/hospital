<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Eyes_patient_prescription_history extends CI_Controller {
 
	function __construct() 
	{
		parent::__construct();	
		auth_users();  
		$this->load->model('eye_prescription/prescription_model','prescription');
    }


  public function history($patient_id='')
  {
      //unauthorise_permission('351','2134');
      $data['page_title'] = 'Eye Prescription History List';
      $data['list'] = $this->prescription->get_patient_pres_history($patient_id);  
      $this->load->view('eye_prescription/history_list',$data);
  }

    public function ajax_list()
    {
        //unauthorise_permission('351','2134');
        $users_data = $this->session->userdata('auth_users');
        $this->load->model('opd/opd_model','opd');
        $list = $this->prescription->get_datatables();  

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
              $d_status='';
              if($prescription->dilate_status==0)
                {
                  $d_status = '<span style="color:orange!important;"> Undilate </span>';
                }
                else if($prescription->dilate_status==1){
                   $d_status = '<span style="color:red!important;"> D </span>';
                }
                else if($prescription->dilate_status==2){
                   $d_status = '<span style="color:green!important;"> Dilated </span>';
                }    


            if($prescription->app_type==0)
            {
                $app_type = '<font style="background-color: rgb(147, 255, 51);">New</font>';
            }   
            else if($prescription->app_type==1){
                $app_type = '<font style="background-color: rgb(51, 255, 215);">Review</font>';
            }                 
            else if($prescription->app_type==2){
                $app_type = '<font style="background-color: rgb(255, 51, 172);">Post OP</font>';
            } 
            $pat_status='';
            $patient_status=$this->opd->get_by_id_patient_details($prescription->booking_id);

            if($patient_status['completed']=='1')
            {
              $pat_status = '<font style="background-color: #1CAF9A;color:white">Completed</font>';
            }
            else if($patient_status['doctor']=='1')
            {
              $pat_status = '<font style="background-color: #1CAF9A;color:white">Doctor</font>';
            }
            else if($patient_status['optimetrist']=='1')
            {
              $pat_status = '<font style="background-color: #1CAF9A;color:white">Opt.Optom</font>';
            }
            else if($patient_status['reception']=='1')
            {
              $pat_status = '<font style="background-color: #1CAF9A;color:white">reception</font>';
            }
            else if($patient_status['arrive']=='1')
            {
              $pat_status = '<font style="background-color: #1CAF9A;color:white">Arrived</font>';
            }
            else{
               $pat_status = '<font style="background-color: #1CAF9A;color:white">Not Arrived</font>';
            }        
            $age_y = $prescription->age_y;
                $age_m = $prescription->age_m;
                $age_d = $prescription->age_d;
                $age_h = $prescription->age_h;
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
                if($age_h>0)
                {
                $hours = 'Hours';
                
                $age .= " ".$age_h." ".$hours;
                } 
               


            $row[] = '<input type="checkbox" name="prescription[]" class="checklist" value="'.$prescription->id.'">'.$check_script;
            $row[] = $prescription->booking_code;
            $row[] = $prescription->patient_code;    
            $row[] = $prescription->patient_name;
            $row[] = $prescription->mobile_no;
            $row[] = $age;
            $row[] = $d_status;
            $row[] = $status;
            $row[] = $app_type;
            $row[] = $pat_status; 
            $row[] = date('d-M-Y',strtotime($prescription->created_date));  

            //Action button /////
            $btn_edit = ""; 
            $btn_view = ""; 
            $btn_delete = "";
            $btn_view_pre ="";
            $btn_print_pre="";
            $btn_upload_pre="";
            $btn_view_upload_pre="";
            
            if($users_data['parent_id']==$prescription->branch_id)
            {
                //if(in_array('2132',$users_data['permission']['action'])) 
                //{
                $btn_edit = ' <a class="btn-custom" href="'.base_url("eye/add_eye_prescription/test/".$prescription->booking_id.'/'.$prescription->id).'" title="Edit Prescription"><i class="fa fa-pencil"></i> Edit Eye Prescription</a>';
                //}
                //if(in_array('2133',$users_data['permission']['action'])) 
                //{
                $btn_delete = ' <a class="btn-custom" onClick="return delete_eye_prescription('.$prescription->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a>';
                //}
            }

            //if(in_array('2139',$users_data['permission']['action'])) 
                // {
                    $btn_view_pre = ' <a class="btn-custom"  href="'.base_url('eye/add_eye_prescription/view_prescription/'.$prescription->id.'/'.$prescription->booking_id).'" title="View Eye Prescription" target="_blank" data-url="512"><i class="fa fa-info-circle"></i> View Eye Prescription</a>';
                 //} 
                 //if(in_array('2140',$users_data['permission']['action'])) 
                 //{
                   $print_url = "'".base_url('eye/add_eye_prescription/view_prescription/'.$prescription->id.'/'.$prescription->booking_id)."'";
                  $btn_print_pre = ' <a class="btn-custom" onClick="return print_window_page('.$print_url.')" href="javascript:void(0)" title="Print Eye Prescription"  data-url="512"><i class="fa fa-print"></i> Print Eye Prescription</a>';
                // } 
       

            $row[] =   $btn_view_pre.$btn_print_pre.$btn_upload_pre.$btn_view_upload_pre.$btn_edit.$btn_view.$btn_delete;                
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->prescription->count_all(),
                        "recordsFiltered" => $this->prescription->count_filtered(),
                        "data" => $data,
                );
        echo json_encode($output);
    }

}
?>