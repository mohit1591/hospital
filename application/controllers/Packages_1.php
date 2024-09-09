<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Packages extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('packages/packages_model','packages');
        $this->load->library('form_validation');
    }

    public function index()
    { 
         // unauthorise_permission(24,140);
        $data['page_title'] = 'packages  List'; 
        $this->load->view('packages/packages_list',$data);
    }
    public function packages_ajax_list()
    {

        // unauthorise_permission('11','57');
        $users_data = $this->session->userdata('auth_users');
        $sub_branch_details = $this->session->userdata('sub_branches_data');
        $parent_branch_details = $this->session->userdata('parent_branches_data');
        $list = $this->packages->get_datatables();
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
            foreach ($list as $packages) {
                // print_r($packages);die;
                $no++;
                $row = array();
                if($packages->status==1)
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
            if($users_data['parent_id']==$packages->branch_id){
               $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$packages->id.'">'.$check_script; 
            }else{
               $row[]='';
            }
            $row[] = $packages->title; 
            $row[]=$packages->amount; 
            $row[] = $status;
            $row[] = date('d-M-Y H:i A',strtotime($packages->created_date)); 
            
          $btnedit='';
          $btndelete='';
          
          if($users_data['parent_id']==$packages->branch_id){
               if(in_array('59',$users_data['permission']['action'])){
                    $btnedit = ' <a onClick="return edit_packages('.$packages->id.');" class="btn-custom" href="javascript:void(0)" style="'.$packages->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
               }
                if(in_array('60',$users_data['permission']['action'])){
                    $btndelete = ' <a class="btn-custom" onClick="return delete_packages('.$packages->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
               }
          }
             
             $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->packages->count_all(),
                        "recordsFiltered" => $this->packages->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
  
    }

 
 
}