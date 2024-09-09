<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pediatrician_age_chart extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('pediatrician/pediatrician_age_chart/Pediatrician_age_chart_model','age_chart');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('274','1614');
        $data['vaccination_list']= $this->age_chart->vaccination_list();
        //echo "<pre>"; print_r($data['vaccination_list']);
        $data['age_list']= $this->age_chart->age_list();
        $data['list'] = $this->age_chart->get_datatables(); 
        $data['page_title'] = 'Pediatrician Chart'; 
        $this->load->view('pediatrician/pediatrician_age_chart/list',$data);
    }

    public function ajax_list()
    { 
        
        $list = $this->age_chart->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        $vaccination_ids=array();
       
        foreach ($list as $age_chart) {

         // print_r($relation);die;
            $no++;
            $row = array();
            if($age_chart->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            } 
            
            ////////// Check  List /////////////////medicine_preferred_reminder_service
            $check_script = "";
            if($i==$total_num)
            {
            /*$check_script = "<script>$('#selectAll').on('click', function () { 
                                  if ($(this).hasClass('allChecked')) {
                                      $('.checklist').prop('checked', false);
                                  } else {
                                      $('.checklist').prop('checked', true);
                                  }
                                  $(this).toggleClass('allChecked');
                              })</script>";*/
            }                 
            ////////// Check list end ///////////// 
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$age_chart->id.'">'.$check_script; 
            $row[] = ''; 
            $row[] = ''; 
            // $ages_li= $this->age_vaccination->get_age_list_according_vaccine($age_vaccination->id);  
            // $row[]=$ages_li;
            $row[]='';
            $row[]='';
            $row[]='';
            $row[]='';
            $row[] = $status;
            $row[] = date('d-M-Y H:i A',strtotime($age_chart->created_date)); 
            $users_data = $this->session->userdata('auth_users');
            $btnedit='';
            $btndelete='';
          if(in_array('1614',$users_data['permission']['action'])){
               $btnedit = ' <a onClick="return edit_age_vaccination('.$age_chart->id.');" class="btn-custom" href="javascript:void(0)" style="'.$age_chart->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
          }
          if(in_array('1614',$users_data['permission']['action'])){
               $btndelete = ' <a class="btn-custom" onClick="return delete_age_vaccination('.$age_chart->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
          }
          $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->age_chart->count_all(),
                        "recordsFiltered" => $this->age_chart->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
   

 

}
?>